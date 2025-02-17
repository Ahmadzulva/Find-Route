<?php

class RouteFinder {
  private string $destination;
  private array $result; 

  public function __construct(private array $map) {}

  public function findFastestRoute(string $source, string $destination): array {
    if (!$this->isValidSource($source)) {
      echo "Source tidak valid" . PHP_EOL;
      die;
    }
    if (!$this->isValidDestination($destination)) {
      echo "Destination tidak valid!" . PHP_EOL;
      die;
    }
    $this->destination = $destination;
    $this->result = [];

    $this->search($source);
    return $this->getFastestRoute();
  }

  private function search(string $currentPosition, int $currentTime = 0, string $route = '', int $totalTime = 0): void {
    $route .= $currentPosition;
    $totalTime += $currentTime;

    if ($currentPosition === $this->destination) {
      array_push($this->result, [$route, $totalTime]);
      return;
    }

    foreach ($this->map[$currentPosition] as $position => $time) {
      if (!str_contains($route, $position)) {
        $this->search($position, $time, $route, $totalTime);
      }
    }
  }

  private function getFastestRoute(): array {
    if (count($this->result) === 0) {
      return ['', 0];
    }
    $route = $this->result[0][0];
    $time = $this->result[0][1]; // Fix the typo here, should be [1] instead of [0]
    foreach ($this->result as $row) {
      if ($row[1] < $time) {
        $route = $row[0];
        $time = $row[1];
      }
    }
    return [$route, $time];
  }

  private function isValidSource(string $source): bool {
    return !empty($this->map[$source]);
  }

  private function isValidDestination(string $destination): bool {
    foreach ($this->map as $position) {
      if (!empty($position[$destination]))
        return true;
    }
    return false;
  }
}
