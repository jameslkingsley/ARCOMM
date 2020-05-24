<?php
namespace App\Helpers\PBOMission;

use App\Helpers\PBOMission\Mission\SQMConfig;
use App\Helpers\PBOMission\PBOFile\PBOFile;
use App\Helpers\PBOMission\Mission\Mission;

class PBOMissionHelper {
  public static function getReadableSize($size) {
    $unit = array('B','KB','MB','GB','TB','PB');
    return @round($size/pow(1024,($i=floor(log($size,1024)))),2).$unit[$i];
  }
}

class PBOMission {
  public PBOFile $pbo;
  public Mission $mission;
  public bool $error = false;
  public string $errorReason;

  private static $errorReasons = array(
    'EMPTY_MISSION' => 'Błąd odczytu pliku misji (mission.sqm) lub jego brak.',
    'INVALID_MAP' => 'Niepoprawna nazwa mapy. Sprawdź nazwę pliku.',
    'XML_ERROR' => 'Błąd parsowania xml (stringtable.xml). Powód: %s'
  );

  function __construct(string $filepath, ?string $filename = null) {
    if (!$filename) $filename = basename($filepath);

    $this->pbo = new PBOFile($filepath, $filename);

    if ($this->pbo->error) {
      $this->error = true;
      return $this->errorReason = $this->pbo->errorReason;
    }

    // Get mission config content
    $missionContent = $this->pbo->getFileContent('mission.sqm');

    // Debinarize if needed, if it's not binarized it will do nothing
    /*$missionTempFile = tmpfile();
    $missionDebinarizedTempFile = tmpfile();

    fwrite($missionTempFile, $missionContent);

    //shell_exec(static::armake() . 'derapify -f {{$missionTempFile}} {{$missionDebinarizedTempFile}}'); //TODO: Uncomment when pushing to prod
    shell_exec("derap -f {{$missionTempFile}} {{$missionDebinarizedTempFile}}");
    fseek($missionDebinarizedTempFile, 0);
    $missionContent = fread($missionDebinarizedTempFile, 1024);    

    fclose($missionTempFile);
    fclose($missionDebinarizedTempFile);*/ //TODO: Fix derap

    if (!isset($missionContent) || $missionContent == '') {
      $this->error = true;
      return $this->errorReason = self::$errorReasons['EMPTY_MISSION'];
    }

    // Parse mission config
    $missionConfig = new SQMConfig($missionContent);

    if ($missionConfig->error) {
      $this->error = $missionConfig->error;
      $this->errorReason = $missionConfig->errorReason;
      return;
    };

    // Get mission map
    $nameElements = explode('.', $this->pbo->name);
    $map = end($nameElements);

    if ($map == '') {
      $this->error = true;
      return $this->errorReason = self::$errorReasons['INVALID_MAP'];
    }

    // Parse stringtable xml (if present)
    $stringtable = $this->getStringtable($this->pbo->getFileContent('stringtable.xml'));
    if ($this->error) return;

    // Parse mission
    $this->mission = new Mission($missionConfig->root, $map, $stringtable);

    if ($this->mission->error) {
      $this->error = true;
      return $this->errorReason = $this->mission->errorReason;
    }
  }

  private function getStringtable(?string $xmlContent): ?array {
    if (!isset($xmlContent) || $xmlContent == '') return null;
    libxml_use_internal_errors(true);

    $xmlObject = simplexml_load_string($xmlContent);
    foreach (libxml_get_errors() as $error) {
      $this->error = true;
      $this->errorReason = sprintf(self::$errorReasons['XML_ERROR'], $error->message);
      return null;
    }

    $keys = $xmlObject->xpath('/Project/Package/Key');
    if ($keys === false) return null;

    $stringtable = array();
    $langs = array('Original', 'Polish', 'English');

    foreach ($keys as $key) {
      $attributes = $key->attributes();
      if ($attributes['ID'] == null) continue;
      $id = (string) $attributes['ID'];

      foreach ($langs as $lang) {
        if ($key->{$lang} == null) continue;

        $value = (string) $key->{$lang};
        if ($value == '') continue;

        $stringtable[$id] = $value;
        break;
      }
    }

    if (count($stringtable) == 0) return null;
    return $stringtable;
  }

  public function export(): array {
    if ($this->error) return array('error' => true, 'errorReason' => $this->errorReason);

    return array(
      'error' => false,
      'pbo' => $this->pbo->info,
      'mission' => $this->mission->export()
    );
  }
}

?>
