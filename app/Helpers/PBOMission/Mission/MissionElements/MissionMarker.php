<?php
  namespace App\Helpers\PBOMission\Mission\MissionElements;

  use App\Helpers\PBOMission\Mission\SQMClass;

  define('MISSION_MARKER_EXPORT_KEYS',
  array('id','text','class','color','position','angle'));

  class MissionMarker {
    public int $id;
    public string $class;
    public ?string $text;
    public ?string $color;
    public ?array $position;
    public ?float $angle;

    function __construct(SQMClass $marker) {
      $this->id = $marker->attribute('id');
      $this->class = $marker->attribute('type');
      $this->text = $marker->attribute('text');
      $this->color = $marker->attribute('colorName');
      $this->position = $marker->attribute('position[]');
      $this->angle = $marker->attribute('angle', 0);
    }

    public function export(): array {
      $data = array();

      foreach (MISSION_MARKER_EXPORT_KEYS as $key)
        if (isset($this->{$key})) $data[$key] = $this->{$key};

      return $data;
    }
  }
?>
