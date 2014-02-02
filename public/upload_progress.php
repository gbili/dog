<?php
if (!isset($_GET['id']) && !preg_match('/[A-Za-z0-9]+/', $_GET['id'])) {
    die('Bad Request');
}

$vendorDir = realpath(__DIR__ . '/../vendor');
$zf2Path = $vendorDir . '/zendframework/zendframework/library';
$loader = include $vendorDir . '/autoload.php';
$loader->add('Zend', $zf2Path);

$sessionProgress = new \Zend\ProgressBar\Upload\SessionProgress();
$model = new \Zend\View\Model\JsonModel($sessionProgress->getProgress($_GET['id']));

echo $model->serialize();

unset($model, $sessionProgress, $vendorDir, $zf2Path, $loader);
