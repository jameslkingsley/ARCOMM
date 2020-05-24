<?php
  namespace App\Helpers\PBOMission\Mission\MissionElements;

  use App\Helpers\PBOMission\Mission\SQMClass;

  class MissionObject {
    public int $id;
    public string $class;
    public bool $isSimple = false;

    function __construct(SQMClass $object) {
      $this->id = $object->attribute('id');
      $this->class = $object->attribute('type');

      if ($attributes = $object->class('Attributes'))
        $this->isSimple = (bool) $attributes->attribute('createAsSimpleObject', false);
    }
  }
?>
