import azure.cognitiveservices.speech as speechsdk
from src.config import SPEECH_KEY, SPEECH_REGION
# from src.utils import text_parse
# from pydub import AudioSegment
# import io


SUBSCRIPTION = SPEECH_KEY
REGION = SPEECH_REGION

speech_config = speechsdk.SpeechConfig(subscription=SUBSCRIPTION, region=REGION)
audio_config = speechsdk.audio.AudioOutputConfig(filename="output.wav")

# The language of the voice that speaks.
speech_config.speech_synthesis_voice_name='en-IN-PrabhatNeural'

speech_synthesizer = speechsdk.SpeechSynthesizer(speech_config=speech_config, audio_config=audio_config)


def text_to_speech_azure(text):
    
    try:
        # print("Speech sysnthesis start")
        speech_synthesis_result = speech_synthesizer.speak_text_async(text).get()
        
        if speech_synthesis_result.reason == speechsdk.ResultReason.SynthesizingAudioCompleted:
            # print("Speech synthesized for text Completed")
            audio_stream = speech_synthesis_result.audio_data
            print("Speech sysnthesis finished")
            return audio_stream
        
        elif speech_synthesis_result.reason == speechsdk.ResultReason.Canceled:
            cancellation_details = speech_synthesis_result.cancellation_details
            print("Speech synthesis canceled: {}".format(cancellation_details.reason))
            
            if cancellation_details.reason == speechsdk.CancellationReason.Error:
                
                if cancellation_details.error_details:
                    
                    print("Error details: {}".format(cancellation_details.error_details))
                    print("Did you set the speech resource key and region values?")
                    
        return None 
    
    
    except Exception as e:
        print(str(e))
