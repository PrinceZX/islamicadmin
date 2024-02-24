import os 
from dotenv import load_dotenv
load_dotenv()

LOG_PATH = os.path.join(os.getcwd(), "logs")

SPEECH_KEY = os.getenv("SPEECH_KEY")

SPEECH_REGION = os.getenv("SPEECH_REGION")



CHUNK_SIZE = 50