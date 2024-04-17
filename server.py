# -*- coding: utf-8 -*-
import subprocess
from flask import Flask, request, jsonify

app = Flask(__name__)

@app.route('/json', methods=['POST'])
def upload_file():
    if 'file' not in request.files:
        return jsonify({'error': 'No file part'})

    file = request.files['file']
    if file.filename == '':
        return jsonify({'error': 'No selected file'})

    # Lesen der Dateiinhalt
    file_contents = file.read()

    # Ausführen des magika-Befehls
    command = ['magika', '--json', '-']
    result = subprocess.run(command, input=file_contents, capture_output=True)

    if result.returncode == 0:
        return jsonify({'result': result.stdout.decode()})  # Dekodiere die Ausgabe
    else:
        return jsonify({'error': result.stderr.decode()})  # Dekodiere den Fehler

if __name__ == '__main__':
    app.run(debug=True, host='0.0.0.0', port=5000)
