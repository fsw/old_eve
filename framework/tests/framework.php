<?php

test('test1', true);
test('test2', true);
test('test3', true);


Site::route(new Request('/'));

Site::route(new Request('/static/cado/cado.png'));

//index page?
