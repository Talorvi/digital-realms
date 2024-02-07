<?php

namespace App\Services;

class DigimonEvolutionService
{
    protected $baseDir;
    protected $evolutionTree = [];
    protected $processed = [];

    public function __construct()
    {
        $this->baseDir = app_path('Models/Digimon');
    }

    public function buildEvolutionTree($rootName = 'Botamon'): array
    {
        $this->evolutionTree = []; // Reset the tree to handle multiple requests correctly
        $this->processed = []; // Reset processed files
        $this->processDirectory($this->baseDir . '/Fresh');
        if (!array_key_exists($rootName, $this->evolutionTree)) {
            return [];
        }
        $nestedTree = $this->convertToNested($this->evolutionTree, $rootName);
        return $nestedTree;
    }

    protected function processDirectory($dir): void
    {
        $files = array_diff(scandir($dir), ['..', '.']);
        foreach ($files as $file) {
            $filePath = $dir . '/' . $file;
            if (is_file($filePath)) {
                $this->extractEvolutionData($filePath);
            } elseif (is_dir($filePath)) {
                // Recursively process subdirectories
                $this->processDirectory($filePath);
            }
        }
    }

    protected function extractEvolutionData($filePath): void
    {
        // Prevent processing the same file multiple times
        if (in_array($filePath, $this->processed)) {
            return;
        }
        $this->processed[] = $filePath; // Mark as processed

        $content = file_get_contents($filePath);
        preg_match('/class\s+(\w+)/', $content, $classMatches);
        preg_match_all('/return\s+new\s+(\w+)\(\)/', $content, $evolutionMatches);

        if (!empty($classMatches[1])) {
            $className = $classMatches[1];
            $evolutions = array_unique($evolutionMatches[1]);

            // Directly map the class name to its evolutions without stages
            $this->evolutionTree[$className] = $evolutions;

            // Recursively process the evolution files
            foreach ($evolutions as $evolution) {
                $evolutionFilePath = $this->findFilePathForClass($evolution);

                if ($evolutionFilePath) {
                    $this->extractEvolutionData($evolutionFilePath);
                }
            }
        }
    }

    protected function findFilePathForClass($className)
    {
        // Attempt to find the file path for a given class name by searching through the Digimon model directories
        $directories = new \RecursiveDirectoryIterator($this->baseDir);
        $iterator = new \RecursiveIteratorIterator($directories);
        foreach ($iterator as $file) {
            if ($file->isFile()) {
                // Extract the base name without the extension to compare to the class name
                $filenameWithoutExtension = pathinfo($file->getFilename(), PATHINFO_FILENAME);
                if ($filenameWithoutExtension === $className) {
                    return $file->getPathname();
                }
            }
        }
        return null;
    }

    protected function convertToNested($flatTree, $rootName): array
    {
        $nestedTree = ['name' => $rootName, 'children' => []];
        $this->buildNestedTree($nestedTree, $flatTree);
        return $nestedTree;
    }

    protected function buildNestedTree(&$node, $flatTree): void
    {
        if (array_key_exists($node['name'], $flatTree)) {
            foreach ($flatTree[$node['name']] as $childName) {
                $childNode = ['name' => $childName, 'children' => []];
                $this->buildNestedTree($childNode, $flatTree);
                $node['children'][] = $childNode;
            }
        }
    }
}
