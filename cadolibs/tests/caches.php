<?php

cache_Array::set('test', 123);
$ret = cache_Array::get('test');

test('cache_Array', $ret === 123);


cache_Apc::set('test', 123);
$ret = cache_Apc::get('test');
test('cache_Apc', $ret === 123);