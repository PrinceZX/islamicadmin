from concurrent.futures import ThreadPoolExecutor
from pydub import AudioSegment
from src.audio import AudioSynthesis
import io
from src.utils import chunk_text
import azure.cognitiveservices.speech as speechsdk
from src.config import SPEECH_KEY, SPEECH_REGION


def convert_text_to_speech_with_threading(voice_name, text, chunk_size):
    
    try:
        voice_synthesis = AudioSynthesis(voice_name)
        chunks = chunk_text(text, chunk_size)

        with ThreadPoolExecutor() as executor:
            
            print("threading start")
            
            audio_streams = executor.map(voice_synthesis.text_to_speech_azure, chunks)
            audio_segments = [AudioSegment.from_wav(io.BytesIO(audio_bytes)) for audio_bytes in audio_streams]
            
            merged_audio = audio_segments[0]
            for segment in audio_segments[1:]:
                merged_audio += segment
                
            # print(merged_audio)
            print("threading finish")
            
            final_audio = io.BytesIO()
            merged_audio.export(final_audio, format='wav')
            final_audio.seek(0)
            
            return final_audio

    except Exception as e:
        print(str(e))


