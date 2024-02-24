from flask import Flask, request, send_file
from src.parallel_process_azure import convert_text_to_speech_with_threading
from src.config import CHUNK_SIZE
from src.utils import text_parse
# import logging
# import warnings
# from datetime import datetime
# import os 
# from src.logger import setup_logger
# from src.text_to_speech import text_to_audio

# LOG_PATH = os.path.join(os.getcwd(), "logs")

# warnings.filterwarnings("ignore")

# os.makedirs(LOG_PATH, exist_ok=True)

# date = datetime.now().strftime("%Y_%m_%d")

# log = setup_logger(
#     out_file=f"{LOG_PATH}/{date}.log", stderr_level=logging.INFO
# )

app = Flask(__name__)


@app.route("/")
def just_check():
    return "<p>Welcome to Text-to-Speech project</p>"


@app.route("/text", methods=["POST"])
def text_process():
    
    try:
        
        if request.method == "POST":
            print("started")
            
            text = request.json['text']      
            voice_name = request.json['voice_name']
            chunk_size = CHUNK_SIZE
        
            audio_stream = convert_text_to_speech_with_threading(voice_name, text, chunk_size)
            
            if audio_stream:              
                return send_file(audio_stream, as_attachment=True, download_name="audio.wav")
            
            else:
                return "Error occurred--Audio stream not found"

        else:
            return "enter valid Method"

    except Exception as e:
        print(str(e))
        return "Error occurred"
        
@app.route("/file", methods=["POST"])
def file_process():
    
    try:
        
        if request.method == "POST":
            print("started")
                       
            text_file = request.files['file']
            voice_name = request.form.get('voice_name')
            
            text = text_file.read().decode('utf-8')
            chunk_size = CHUNK_SIZE
            
            audio_stream = convert_text_to_speech_with_treadding(voice_name, text, chunk_size)
        
            if audio_stream:              
                return send_file(audio_stream, as_attachment=True, download_name="audio.wav")
            
            else:
                return "Error occurred--Audio stream not found"
                
        else:
            return "enter valid Method"       
                
                   
    except Exception as e:
        print(str(e))
        
        
# @app.route("/image", methods=["POST"])
# def image_process():
    
#     try:
              
           
        
#     except Exception as e:
#         print(str(e))
        
        
        
if __name__ == '__main__':
    app.run(debug=True)