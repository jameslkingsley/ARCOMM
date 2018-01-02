<?php

namespace App\Models\Missions;

use File;
use Storage;
use \stdClass;
use Carbon\Carbon;
use App\Helpers\ArmaConfig;
use App\Helpers\ArmaScript;
use App\Models\Portal\User;
use Spatie\MediaLibrary\Media;
use App\Helpers\ArmaConfigError;
use App\Notifications\MissionUpdated;
use App\Notifications\MissionVerified;
use App\Notifications\MissionNoteAdded;
use Illuminate\Database\Eloquent\Model;
use App\Notifications\MissionPublished;
use Illuminate\Notifications\Notifiable;
use App\Notifications\MentionedInComment;
use Kingsley\Mentions\Traits\HasMentions;
use App\Notifications\MissionCommentAdded;
use App\Models\Operations\OperationMission;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\HasMedia\Interfaces\HasMediaConversions;

class Mission extends Model implements HasMediaConversions
{
    use Notifiable,
        HasMediaTrait,
        HasMentions;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
        'last_played'
    ];

    /**
     * The loadout role map.
     *
     * @var array
     */
    protected $roles = [
        'all' => 'Everyone',
        'co' => 'Commander',
        'dc' => 'Squad Leader',
        'ftl' => 'Fireteam Leader',
        'm' => 'Medic',
        'fac' => 'Forward Air Controller',
        'r' => 'Rifleman',
        'ar' => 'Automatic Rifleman',
        'aar' => 'Assistant Automatic Rifleman',
        'rat' => 'Rifleman (AT)',
        'mmgtl' => 'Medium MG Team Leader',
        'mmgg' => 'Medium MG Gunner',
        'mmgab' => 'Medium MG Ammo Bearer',
        'mattl' => 'Medium AT Team Leader',
        'matg' => 'Medium AT Missile Specialist',
        'matab' => 'Medium AT Assistant Missile Specialist',
        'mtrl' => 'Mortar Team Leader',
        'mtrg' => 'Mortar Gunner',
        'mtra' => 'Mortar Assistant',
        'p' => 'Pilot',
        'cp' => 'Co-Pilot',
        'vc' => 'Vehicle Commander',
        'vd' => 'Vehicle Driver',
        'vg' => 'Vehicle Gunner'
    ];

    /**
     * Game mode full texts.
     *
     * @var array
     */
    protected $gamemodes = [
        'coop' => 'Cooperative',
        'adversarial' => 'Adversarial',
        'preop' => 'Pre-Operation'
    ];

    /**
     * Discord notification channel.
     *
     * @return any
     */
    public function routeNotificationForDiscord()
    {
        return config('services.discord.channel_id');
    }

    /**
     * Media library image conversions.
     *
     * @return void
     */
    public function registerMediaConversions(Media $media = null)
    {
        $this->addMediaConversion('thumb')
            ->width(384)
            ->height(384)
            ->quality(100)
            ->nonQueued()
            ->performOnCollections('images');
    }

    /**
     * Gets the unread comments on the mission.
     *
     * @return Collection App\Models\Missions\MissionComment
     */
    public function unreadComments()
    {
        $mission = $this;

        $filtered = auth()->user()->unreadNotifications->filter(function ($item) use ($mission) {
            return
                $item->type == MissionCommentAdded::class &&
                $item->data['comment']['mission_id'] == $mission->id;
        });

        return $filtered;
    }

    /**
     * Gets all past missions (last played is in past and not null).
     *
     * @return Collection App\Models\Missions\Mission
     */
    public static function allPast()
    {
        return static::where('last_played', '!=', null)
            ->where('last_played', '<', Carbon::now()->toDateTimeString())
            ->where('verified', true)
            ->orderBy('last_played', 'desc')
            ->get();
    }

    /**
     * Gets all new missions (last played is null).
     *
     * @return Collection App\Models\Missions\Mission
     */
    public static function allNew()
    {
        return static::where('last_played', null)
            ->where('verified', true)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Checks whether the mission is new.
     *
     * @return boolean
     */
    public function isNew()
    {
        return is_null($this->last_played);
    }

    /**
     * Gets the missions map.
     *
     * @return App\Models\Missions\Map
     */
    public function map()
    {
        return $this->belongsTo(Map::class);
    }

    /**
     * Gets the verified by user model.
     *
     * @return App\Models\Portal\User
     */
    public function verifiedByUser()
    {
        return User::where('id', $this->verified_by)->first();
    }

    /**
     * Gets the mission's user (author).
     *
     * @return App\Models\Portal\User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Gets the full mission URL.
     *
     * @return string
     */
    public function url($uri = '')
    {
        return url("/hub/missions/{$this->id}/{$uri}");
    }

    /**
     * Checks whether the mission belongs to the authenticated user.
     *
     * @return boolean
     */
    public function isMine()
    {
        return $this->user_id == auth()->user()->id;
    }

    /**
     * Gets all mission comments.
     *
     * @return Collection App\Models\Missions\MissionComment
     */
    public function comments()
    {
        return $this->hasMany(MissionComment::class);
    }

    /**
     * Gets all notes for the mission.
     *
     * @return Collection App\Models\Missions\MissionNote
     */
    public function notes()
    {
        return $this->hasMany(MissionNote::class);
    }

    /**
     * Gets all revisions for the mission.
     *
     * @return Collection App\Models\Missions\MissionRevision
     */
    public function revisions()
    {
        return $this->hasMany('App\Models\Missions\MissionRevision');
    }

    /**
     * Gets the mission banner URL.
     *
     * @return string
     */
    public function banner()
    {
        $media = $this->photos();

        if (count($media) > 0) {
            return $media[0]->getUrl();
        } else {
            return '';
        }
    }

    /**
     * Gets the mission thumbnail URL.
     *
     * @return string
     */
    public function thumbnail()
    {
        if (env('APP_ENV', 'production') == 'debug') {
            return '';
        }

        $media = $this->photos();

        if (count($media) > 0) {
            return $media[0]->getUrl('thumb');
        }

        if (!is_null($this->map->image_2d)) {
            return url($this->map->image_2d);
        }

        // return url('/images/arcomm-placeholder.jpg');
        return '';
    }

    /**
     * Gets a full text string of the game mode.
     *
     * @return string
     */
    public function fulltextGamemode()
    {
        return $this->gamemodes[strtolower($this->mode)];
    }

    /**
     * Gets the exported name of the file following the mission name format.
     *
     * @return string
     */
    public function exportedName($format = 'pbo')
    {
        $download = 'ARC_' .
            strtoupper($this->mode == 'adversarial' ? 'tvt' : $this->mode) . '_' .
            studly_case($this->display_name) . '_' .
            trim(substr($this->user->username, 0, 4)) . '_' .
            $this->id . '.' .
            $this->map->class_name . '.' . $format;

        return $download;
    }

    /**
     * Gets all videos for the mission.
     * Sorted latest first.
     *
     * @return Collection App\Models\Portal\Video
     */
    public function videos()
    {
        return $this->hasMany('App\Models\Portal\Video')->orderBy('created_at', 'desc');
    }

    /**
     * Gets all photos for the mission.
     *
     * @return Collection
     */
    public function photos()
    {
        return $this->getMedia('images');
    }

    /**
     * Sets the given briefing faction to locked or unlocked.
     *
     * @return void
     */
    public function lockBriefing($faction, $state)
    {
        $this->{'locked_' . strtolower($faction) . '_briefing'} = $state;
        $this->save();
    }

    /**
     * Gets the user's draft for the mission.
     *
     * @return App\Models\Missions\MissionComment
     */
    public function draft()
    {
        $comment = MissionComment::
            where('mission_id', $this->id)
            ->where('user_id', auth()->user()->id)
            ->where('published', false)
            ->first();

        return $comment;
    }

    /**
     * Checks whether the given mission exists in an operation.
     *
     * @return boolean
     */
    public function existsInOperation()
    {
        $item = OperationMission::where('mission_id', $this->id)->first();

        if ($item) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Gets the full path of the armake exe.
     *
     * @return string
     */
    public static function armake()
    {
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            return resource_path('utils/armake.exe');
        } else {
            return 'armake';
        }
    }

    /**
     * Creates the downloadable file and returns its full URL.
     *
     * @return string
     */
    public function download($format = 'pbo')
    {
        $path_to_file = ($format == 'pbo') ? $this->cloud_pbo : $this->cloud_zip;

        if (strlen($path_to_file) == 0) {
            $path_to_file = "missions/{$this->user_id}/{$this->id}/{$this->exportedName($format)}";
        }

        $command = (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') ?
            'gsutil ' :
            '/usr/bin/python /usr/lib/google-cloud-sdk/bin/bootstrapping/gsutil.py ';

        $signed_url = shell_exec(
            $command .
            'signurl -d 10m ' .
            base_path('gcs.json') .
            ' gs://archub/' . $path_to_file .
            ' 2>&1' // Needed to get output
        );

        return trim(preg_replace('/([\s\S]+)https:\/\/storage/', 'https://storage', $signed_url));
    }

    /**
     * Unpacks the mission PBO and returns the absolute path of the folder.
     *
     * @return string
     */
    public function unpack($dirname = '', $pbo_path = '')
    {
        $pbo_path = ($pbo_path == '') ? storage_path("app/{$this->pbo_path}") : $pbo_path;
        $unpacked = ($dirname == '') ?
            storage_path("app/missions/{$this->user_id}/{$this->id}/unpacked") :
            storage_path("app/missions/{$this->user_id}/{$dirname}");

        // It should always be the most up-to-date
        // as we delete unpacked after using them
        if (file_exists($unpacked)) {
            return $unpacked;
        }

        // Unpack the PBO
        shell_exec(static::armake() . ' unpack -f ' . $pbo_path . ' ' . $unpacked);

        $workingDir = getcwd();
        chdir($unpacked);

        // Debinarize mission.sqm
        // If it's not binned, armake exits gracefully
        shell_exec(static::armake() . ' derapify -f mission.sqm mission.sqm');

        chdir($workingDir);

        return $unpacked;
    }

    /**
     * Deletes the unpacked mission directory.
     *
     * @return void
     */
    public function deleteUnpacked($dirname = '')
    {
        $unpacked = ($dirname == '') ?
            storage_path("missions/{$this->user_id}/{$this->id}/unpacked") :
            storage_path("missions/{$this->user_id}/{$dirname}");

        Storage::deleteDirectory($unpacked);
    }

    /**
     * Gets the mission EXT object.
     *
     * @return object
     */
    public function ext()
    {
        return json_decode($this->ext_json);
    }

    /**
     * Gets the mission SQM object.
     *
     * @return object
     */
    public function sqm()
    {
        return json_decode($this->sqm_json);
    }

    /**
     * Gets the mission config object.
     *
     * @return object
     */
    public function config()
    {
        return json_decode($this->cfg_json)->cfgarcmf;
    }

    /**
     * Stores the decoded mission config objects in the session.
     * Used for optimisation.
     *
     * @return void
     */
    public function storeConfigs($before_closure = null, $after_closure = null, $pbo_path = '')
    {
        $unpacked = $this->unpack('', $pbo_path);

        // Run closure with raw unpacked directory
        if (!is_null($before_closure)) {
            $before_closure($this, $unpacked);
        }

        // Return error if these files are missing
        foreach (['mission.sqm', 'description.ext', 'config.hpp'] as $required_file) {
            if (!file_exists("{$unpacked}/{$required_file}")) {
                return new ArmaConfigError("{$required_file} is missing from the mission file");
            }
        }

        // Try to convert configs
        $ext_obj = ArmaConfig::convert("{$unpacked}/description.ext");
        $cfg_obj = ArmaConfig::convert("{$unpacked}/config.hpp");
        $version = file_get_contents("{$unpacked}/version.txt");

        // If any errors with configs, return error object
        foreach ([$ext_obj, $cfg_obj] as $parsedObject) {
            if (get_class($parsedObject) == 'App\Helpers\ArmaConfigError') {
                $this->deleteUnpacked();
                return $parsedObject;
            }
        }

        // Check loadout files since these are common places for errors
        // TODO Doesn't check whole directory until new ARCMF version is used
        $sqf_result = ArmaScript::check("{$unpacked}/f/assignGear/");

        if (strlen(trim($sqf_result)) != 0) {
            return new ArmaConfigError($sqf_result);
        }

        // No errors so far, so store configs in mission as JSON
        $this->ext_json = json_encode($ext_obj);
        $this->cfg_json = json_encode($cfg_obj);
        $this->version = $version;
        $this->save();

        // Run closure with raw unpacked directory
        if (!is_null($after_closure)) {
            $after_closure($this, $unpacked, $ext_obj, $cfg_obj);
        }

        // Handle Mission SQM
        // Removes entity data in sqm to avoid Eden string nuances
        $sqm_file = "{$unpacked}/mission.sqm";

        $sqm_contents = file_get_contents($sqm_file);
        $sqm_contents = preg_replace('!/\*.*?\*/!s', '', $sqm_contents);
        $sqm_contents = preg_replace('/(class Entities[\s\S]+)/', '};', $sqm_contents);
        file_put_contents($sqm_file, $sqm_contents);

        $sqm_obj = ArmaConfig::convert($sqm_file);

        $this->sqm_json = json_encode($sqm_obj);
        $this->save();

        // Delete unpacked
        $this->deleteUnpacked();

        // Return config objects
        return (object)[
            'sqm' => $sqm_obj,
            'ext' => $ext_obj,
            'cfg' => $cfg_obj,
            'version' => $version
        ];
    }

    /**
     * Deploys the mission files to cloud storage.
     * - PBO Download
     * - ZIP Download
     *
     * @return any
     */
    public function deployCloudFiles($unpacked)
    {
        $qualified_pbo = "missions/{$this->user_id}/{$this->id}/{$this->exportedName()}";

        // Mission PBO
        Storage::disk('gcs')->put(
            $qualified_pbo,
            file_get_contents(storage_path("app/{$this->pbo_path}"))
        );

        $this->cloud_pbo = $qualified_pbo;

        // Mission ZIP
        $files = glob("{$unpacked}/*");
        $name = $this->exportedName('zip');
        $path = "zips/{$name}";

        $zip = new \Chumper\Zipper\Zipper;
        $zip->make("downloads/{$path}")->add($unpacked)->close();
        $qualified_zip = "missions/{$this->user_id}/{$this->id}/{$name}";

        Storage::disk('gcs')->put(
            $qualified_zip,
            file_get_contents(public_path("downloads/{$path}"))
        );

        $this->cloud_zip = $qualified_zip;

        if (file_exists(public_path("downloads/{$path}"))) {
            unlink(public_path("downloads/{$path}"));
        }

        // Save the new PBO path in the mission
        $this->pbo_path = $qualified_pbo;
        $this->save();
    }

    /**
     * Gets the missions framework version.
     *
     * @return string
     */
    public function version()
    {
        return $this->version;
    }

    /**
     * Computes an array of options and returns the lowest true result.
     *
     * @return string
     */
    protected static function computeLessThan($value, $options)
    {
        foreach ($options as $text => $level) {
            if ($value <= $level) {
                return $text;
            }
        }
    }

    /**
     * Gets the fog text.
     *
     * @return string
     */
    public function fog()
    {
        return static::computeLessThan(
            (property_exists($this->sqm()->mission->intel, 'startfog')) ? $this->sqm()->mission->intel->startfog : 0,
            [
                '' => 0.0,
                'Light Fog' => 0.1,
                'Medium Fog' => 0.3,
                'Heavy Fog' => 0.5,
                'Extreme Fog' => 1.0
            ]
        );
    }

    /**
     * Gets the overcast text.
     *
     * @return string
     */
    public function overcast()
    {
        return static::computeLessThan(
            $this->sqm()->mission->intel->startweather,
            [
                'Clear Skies' => 0.1,
                'Partly Cloudy' => 0.3,
                'Heavy Clouds' => 0.6,
                'Stormy' => 1.0
            ]
        );
    }

    /**
     * Gets the rain text.
     *
     * @return string
     */
    public function rain()
    {
        $startRain = (property_exists($this->sqm()->mission->intel, 'startrain')) ? $this->sqm()->mission->intel->startrain : 0;
        $forecastRain = (property_exists($this->sqm()->mission->intel, 'forecastrain')) ? $this->sqm()->mission->intel->forecastrain : 0;
        $diff = $forecastRain - $startRain;

        return static::computeLessThan(
            $diff,
            [
                '' => 0,
                'Slight Drizzle' => 0.2,
                'Drizzle' => 0.4,
                'Rain' => 0.6,
                'Showers' => 1
            ]
        );
    }

    /**
     * Gets the weather text.
     *
     * @return string
     */
    public function weather()
    {
        return $this->overcast() . (($this->fog() == '') ? '' : ', ' . $this->fog()) . (($this->rain() == '') ? '' : ', ' . $this->rain());
    }

    /**
     * Gets the weather image name.
     *
     * @return string
     */
    public function weatherImage()
    {
        return url('/images/weather/' . ([
            'Clear Skies' => 'clear',
            'Partly Cloudy' => 'partly sunny',
            'Heavy Clouds' => 'partly cloudy',
            'Stormy' => 'cloudy',
            'Clear Skies, Slight Drizzle' => 'slight drizzle',
            'Clear Skies, Drizzle' => 'light rain',
            'Clear Skies, Rain' => 'rain',
            'Clear Skies, Showers' => 'showers',
            'Partly Cloudy, Slight Drizzle' => 'slight drizzle',
            'Partly Cloudy, Drizzle' => 'light rain',
            'Partly Cloudy, Rain' => 'rain',
            'Partly Cloudy, Showers' => 'showers',
            'Heavy Clouds, Slight Drizzle' => 'slight drizzle',
            'Heavy Clouds, Drizzle' => 'light rain',
            'Heavy Clouds, Rain' => 'rain',
            'Heavy Clouds, Showers' => 'showers',
            'Stormy, Slight Drizzle' => 'slight drizzle',
            'Stormy, Drizzle' => 'light rain',
            'Stormy, Rain' => 'rain',
            'Stormy, Showers' => 'showers'
        ])[$this->overcast() . (($this->rain() == '') ? '' : ', ' . $this->rain())] . '.png');
    }

    /**
     * Gets the mission SQM date.
     *
     * @return string
     */
    public function date()
    {
        $date = Carbon::createFromDate(
            (property_exists($this->sqm()->mission->intel, 'year')) ? abs($this->sqm()->mission->intel->year) : 2000,
            (property_exists($this->sqm()->mission->intel, 'month')) ? abs($this->sqm()->mission->intel->month) : 1,
            (property_exists($this->sqm()->mission->intel, 'day')) ? abs($this->sqm()->mission->intel->day) : 1
        );

        return $date->format('jS M Y');
    }

    /**
     * Gets the mission SQM time.
     *
     * @return string
     */
    public function time()
    {
        $time = Carbon::createFromTime(
            (property_exists($this->sqm()->mission->intel, 'hour')) ? abs($this->sqm()->mission->intel->hour) : 0,
            (property_exists($this->sqm()->mission->intel, 'minute')) ? abs($this->sqm()->mission->intel->minute) : 0,
            0
        );

        return $time->format('H:i');
    }

    /**
     * Gets all briefing factions that aren't locked (unless admin).
     *
     * @return array
     */
    public function briefingFactions()
    {
        $filledFactions = [];
        $factions = [
            'BLUFOR' => $this->locked_blufor_briefing,
            'OPFOR' => $this->locked_opfor_briefing,
            'INDFOR' => $this->locked_indfor_briefing,
            'CIVILIAN' => $this->locked_civilian_briefing,
            // 'GAME_MASTER' => $this->locked_gamemaster_briefing
        ];

        foreach ($factions as $faction => $locked) {
            if (!empty($this->briefing($faction)) && (!$locked || auth()->user()->hasPermission('mission:view_locked_briefings') || $this->isMine())) {
                $name = str_replace('_', ' ', $faction);

                $nav = new stdClass();
                $nav->name = $name;
                $nav->faction = $faction;
                $nav->locked = $locked;

                array_push($filledFactions, $nav);
            }
        }

        return $filledFactions;
    }

    /**
     * Gets the given faction's briefing subjects and content.
     *
     * @return array
     */
    public function briefing($faction)
    {
        $faction = strtolower($faction);
        $filledSubjects = [];
        $subjects = [
            'Situation' => 'situation',
            'Mission' => 'mission',
            'Enemy Forces' => 'enemyforces',
            'Friendly Forces' => 'friendlyforces',
            'Commanders Intent' => 'commandersintent',
            'Movement Plan' => 'movementplan',
            'Special Tasks' => 'specialtasks',
            'Fire Support Plan' => 'firesupportplan',
            'Logistics' => 'logistics'
        ];

        foreach ($subjects as $heading => $subject) {
            $subject = strtolower($subject);

            if (!property_exists($this->config()->briefing->$faction, $subject)) {
                continue;
            }

            $paragraphs = (array)$this->config()->briefing->$faction->$subject;

            if (!empty($paragraphs)) {
                $subjectObject = new stdClass();
                $subjectObject->title = $heading;
                $subjectObject->paragraphs = $paragraphs;
                $subjectObject->locked = $this->{'locked_' . $faction . '_briefing'};
                array_push($filledSubjects, $subjectObject);
            }
        }

        return $filledSubjects;
    }

    /**
     * Checks whether the given faction's briefing is locked.
     * Ignores user access level.
     *
     * @return boolean
     */
    public function briefingLocked($faction)
    {
        return $this->{'locked_' . strtolower($faction) . '_briefing'} > 0;
    }

    /**
     * Gets mission mode and map details from name.
     * Validates mission names and aborts if invalid name.
     *
     * @return object
     */
    public static function getDetailsFromName($name)
    {
        if (substr(strtolower($name), -3) != 'pbo') {
            abort(400, 'Mission file must be a PBO');
            return;
        }

        if (strpos($name, '_') === false) {
            abort(400, 'Mission name must be in the format ARC_COOP/TVT/PREOP_Name_Author.Map');
            return;
        }

        $name = rtrim($name, '.pbo');
        $mapName = last(explode('.', $name));
        $parts = explode('_', rtrim($name, ".{$mapName}"));
        $map = Map::whereRaw('LOWER(class_name) = ?', [strtolower($mapName)])->first();

        if (is_null($map)) {
            $map = new Map();
            $map->display_name = $mapName;
            $map->class_name = $mapName;
            $map->save();
        }

        if (sizeof($parts) < 3) {
            abort(400, 'Mission name must be in the format ARC_COOP/TVT/PREOP_Name_Author.Map');
            return;
        }

        $group = $parts[0];
        $mode = strtolower($parts[1]);
        $validModes = ['coop', 'co', 'tvt', 'pvp', 'adv', 'preop'];

        if (in_array($mode, $validModes)) {
            if ($mode == 'co') {
                $mode = 'coop';
            }

            if (in_array($mode, ['tvt', 'pvp', 'adv'])) {
                $mode = 'adversarial';
            }
        } else {
            abort(400, 'Mission game mode is invalid. Must be one of COOP, TVT or PREOP');
            return;
        }

        $details = new stdClass();
        $details->mode = $mode;
        $details->map = $map;

        return $details;
    }

    /**
     * Gets the ACRE languages for the given faction as a string.
     *
     * @return string
     */
    public function acreLanguages($faction = 'blufor')
    {
        $lang = (array)$this->config()->acre->{strtolower($faction)}->languages;

        $mutated = array_map(function ($item) {
            return title_case($item);
        }, $lang);

        return implode(', ', $mutated);
    }

    /**
     * Gets the ACRE role list for the given radio classname and faction.
     *
     * @return string
     */
    public function acreRoles($faction, $radio)
    {
        $roles = (array)$this->config()->acre->{strtolower($faction)}->{strtolower($radio)};

        $mutated = array_map(function ($item) {
            if (array_key_exists(strtolower($item), $this->roles)) {
                return $this->roles[strtolower($item)];
            } else {
                return strtoupper($item);
            };
        }, $roles);

        return implode(', ', $mutated);
    }

    /**
     * Gets an overall description of the comm plan for the given faction.
     * Can be full, limited or none.
     *
     * @return string
     */
    public function acreOverview($faction)
    {
        $radio_343 = (array)$this->config()->acre->{strtolower($faction)}->an_prc_343;

        if (!empty($radio_343)) {
            if (in_array('all', array_map('strtolower', $radio_343))) {
                return 'Full';
            }
        }

        foreach (['AN_PRC_148', 'AN_PRC_152', 'AN_PRC_117F', 'AN_PRC_77'] as $radio) {
            if (!empty((array)$this->config()->acre->{strtolower($faction)}->{strtolower($radio)})) {
                return 'Limited';
            }
        }

        return 'None';
    }

    /**
     * Gets the mission note notifications.
     *
     * @return Collection
     */
    public function noteNotifications()
    {
        $filtered = auth()->user()->unreadNotifications->filter(function ($item) {
            return
                $item->type == MissionNoteAdded::class &&
                $item->data['note']['mission_id'] == $this->id;
        });

        return $filtered;
    }

    /**
     * Gets the mission comments notifications.
     *
     * @return Collection
     */
    public function commentNotifications()
    {
        $filtered = auth()->user()->unreadNotifications->filter(function ($item) {
            return
                ($item->type == MissionCommentAdded::class &&
                $item->data['comment']['mission_id'] == $this->id) ||
                ($item->type == MentionedInComment::class &&
                $item->data['mission_id'] == $this->id);
        });

        return $filtered;
    }

    /**
     * Gets the mission verification notifications.
     *
     * @return Collection
     */
    public function verifiedNotifications()
    {
        $filtered = auth()->user()->unreadNotifications->filter(function ($item) {
            return
                $item->type == MissionVerified::class &&
                $item->data['mission']['id'] == $this->id;
        });

        return $filtered;
    }

    /**
     * Gets the mission updated/published notifications.
     *
     * @return Collection
     */
    public function stateNotifications()
    {
        $filtered = auth()->user()->unreadNotifications->filter(function ($item) {
            return
                ($item->type == MissionPublished::class &&
                $item->data['mission']['id'] == $this->id) ||
                ($item->type == MissionUpdated::class &&
                $item->data['mission_id'] == $this->id);
        });

        return $filtered;
    }
}
