




def text_parse(language, voice_name, voice_style, text):
    parse_text = f"""
    <speak version="1.0" xmlns="http://www.w3.org/2001/10/synthesis" xmlns:mstts="https://www.w3.org/2001/mstts" xml:lang="{language}">
        <voice name="{voice_name}">
            <mstts:express-as style="{voice_style}">
                {text} 
            </mstts:express-as>
        </voice>
    </speak>"""
    
    return parse_text


def chunk_text(text, num_words_per_chunk=20):
    list_of_chunks = []
    words = text.split()
    chunk_str = ""

    for word in words:
        chunk_str += word + " "  # Add the word and a space to separate words
        if len(chunk_str.split()) >= num_words_per_chunk:
            list_of_chunks.append(chunk_str.strip())  # Remove trailing space before adding to list
            chunk_str = ""

    # Append any remaining words to the last chunk
    if chunk_str:
        list_of_chunks.append(chunk_str.strip())

    return list_of_chunks