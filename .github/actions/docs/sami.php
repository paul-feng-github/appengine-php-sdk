<?php

use Sami\RemoteRepository\GitHubRemoteRepository;
use Sami\Sami;
use Sami\Version\GitVersionCollection;
use Symfony\Component\Finder\Finder;

$projectRoot = realpath(__DIR__ . '/../../..') . '/docs/';

echo "Project ROOT: ".$projectRoot ."\n";

$iterator = Finder::create()
    ->files()
    ->name('*.php')
    ->exclude('vendor')
    ->exclude('tests')
    ->in($projectRoot);

$versions = GitVersionCollection::create($projectRoot)
    ->addFromTags(function($tag) {
        return 0 === strpos($tag, '2.');
    })
    ->add('master', 'master branch');

return new Sami($iterator, [
    'title' => 'Appengine PHP SDK for PHP API Reference',
    'build_dir' => $projectRoot . '/.docs/%version%',
    'cache_dir' => $projectRoot . '/.cache/%version%',
    'remote_repository' => new GitHubRemoteRepository('GoogleCloudPlatform/appengine-php-sdk', $projectRoot),
    'versions' => $versions
]);