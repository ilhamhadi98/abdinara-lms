<?php
$g = app(\Illuminate\Contracts\Auth\Access\Gate::class);
$prop = new \ReflectionClass($g);
$p = $prop->getProperty('beforeCallbacks');
$p->setAccessible(true);
$v = $p->getValue($g);
if (isset($v[0])) {
    $closure = $v[0];
    $ref = new \ReflectionFunction($closure);
    var_dump($ref->getFileName() . ':' . $ref->getStartLine());
}
