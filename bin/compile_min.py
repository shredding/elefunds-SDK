#!/usr/bin/env python

import os
import json
from subprocess import call
from sys import stdout

BIN_PATH = os.path.split(os.path.realpath(__file__))[0] + '/'
CSS_PATH = BIN_PATH + '../PHP/src/Elefunds/Template/Shop/Css/'
JS_PATH = BIN_PATH + '../PHP/src/Elefunds/Template/Shop/Javascript/'

ELEFUNDS_LESS = CSS_PATH + 'elefunds.less'
ELEFUNDS_JQUERY = JS_PATH + 'elefunds.jquery.js'
ELEFUNDS_TT_JQUERY = JS_PATH + 'elefundsTT.jquery.js'


if os.path.isfile(ELEFUNDS_LESS):
    config = open(BIN_PATH + 'config.json')
    data = json.load(config)

    AT_THEME = '@theme: '
    AT_THEME_COLOR = '@theme-color: '

    output = ''

    for theme in data['theme']:
        for color in data['theme'][theme]:

            with open(CSS_PATH + 'color.less', 'w') as f:

                # Erase color.less
                f.truncate()

                # Write theme + color to color.less
                output = output + AT_THEME + '"' + theme + '";\n'
                output = output + AT_THEME_COLOR + '"' + color + '";\n'

                f.write(output)

            output = ''

            # Compile less
            min_css = CSS_PATH + 'elefunds_' + theme + '_' + color + '.min.css'

            stdout.write('Compiling elefunds_' + theme + '_' + color + '.min.css... ')
            stdout.flush()

            with open(min_css, 'w') as f:

                call(['lessc', ELEFUNDS_LESS, '--yui-compress'], stdout=f)

            stdout.write('[' + u"\u2713".encode('UTF-8') + ']\n')
            stdout.flush()

else:
    print 'LESS file not found: ' + ELEFUNDS_LESS

if os.path.isfile(ELEFUNDS_JQUERY) and os.path.isfile(ELEFUNDS_TT_JQUERY):

    #Compress JS
    stdout.write('Compressing Javascript... ')
    stdout.flush()

    with open(JS_PATH + 'elefunds.jquery.min.js', 'w') as f:

        f.truncate()
        call(['yuicompressor', ELEFUNDS_TT_JQUERY], stdout=f)
        call(['yuicompressor', ELEFUNDS_JQUERY], stdout=f)

    stdout.write('[' + u"\u2713".encode('UTF-8') + ']\n')
    stdout.flush()

else:
    print 'Javascript files not found!:'
    print ELEFUNDS_JQUERY
    print ELEFUNDS_TT_JQUERY

print ''
exit()
