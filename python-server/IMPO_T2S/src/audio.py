import azure.cognitiveservices.speech as speechsdk
from src.config import SPEECH_KEY, SPEECH_REGION


class AudioSynthesis:
    def __init__(self, voice_name):
        self.voice_name = voice_name
        self.subscription = SPEECH_KEY
        self.region = SPEECH_REGION
        self.speech_config = speechsdk.SpeechConfig(subscription=self.subscription, region=self.region)
        self.audio_config = speechsdk.audio.AudioOutputConfig(filename="output.wav")
        self.speech_config.speech_synthesis_voice_name = self.voice_name
        self.speech_synthesizer = speechsdk.SpeechSynthesizer(speech_config=self.speech_config, audio_config=self.audio_config)

    def text_to_speech_azure(self, text):
        try:
            # print("Speech synthesis start")
            speech_synthesis_result = self.speech_synthesizer.speak_text_async(text).get()

            if speech_synthesis_result.reason == speechsdk.ResultReason.SynthesizingAudioCompleted:
                # print("Speech synthesized for text Completed")
                audio_stream = speech_synthesis_result.audio_data
                print("Speech synthesis finished")
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
