import json
from munkres import Munkres

def application(environ, start_response):
    wsgi_input = environ['wsgi.input']
    length = int(environ.get('CONTENT_LENGTH', 0))
    data = json.loads(wsgi_input.read(length))
    result = Munkres().compute(data)

    start_response('200 OK', [('Content-Type', 'application/json')])
    return [bytes(json.dumps(result), 'utf-8')]
