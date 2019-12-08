import json
from munkres import Munkres

def application(environ, start_response):
    wsgi_input = environ['wsgi.input']
    content_length = int(environ.get('CONTENT_LENGTH', 0))
    data = json.loads(wsgi_input.read(content_length))
    result = Munkres().compute(data)

    start_response('200 OK', [('Content-Type', 'application/json')])
    return [bytes(json.dumps(result), 'utf-8')]
