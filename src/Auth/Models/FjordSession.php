<?php

namespace Fjord\Auth\Models;

use Mobile_Detect;
use DeviceDetector\DeviceDetector;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Session;

class FjordSession extends Model
{
    /**
     * The name of the "created_at" column.
     *
     * @var string
     */
    const CREATED_AT = 'first_activity';

    /**
     * The name of the "updated_at" column.
     *
     * @var string
     */
    const UPDATED_AT = 'last_activity';

    /**
     * Fillable attributes
     *
     * @var array
     */
    protected $fillable = [
        'session_id',
        'fjord_user_id',
        'ip_address',
        'user_agent',
        'payload',
        'last_activity'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'last_activity',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'payload' => 'json',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'last_activity_readable',
        'is_current',
        'browser',
        'device',
        'os'
    ];

    /**
     * Device detector.
     *
     * @var DeviceDetector
     */
    protected $deviceDetector;

    /**
     * Get device detector.
     *
     * @return DeviceDetector
     */
    public function detector()
    {
        if (!$this->deviceDetector) {
            $this->deviceDetector = new DeviceDetector($this->user_agent);
            $this->deviceDetector->parse();
        }

        return $this->deviceDetector;
    }

    /**
     * Readable last_activity
     *
     * @return void
     */
    public function getLastActivityReadableAttribute()
    {
        return $this->last_activity
            ->locale(fjord()->getLocale())
            ->diffForHumans();
    }

    /**
     * GetoOperating system attribute.
     *
     * @return string
     */
    public function getOsAttribute()
    {
        return $this->detector()->getOs()['name'] ?? '';
    }

    /**
     * Get browser attribute.
     *
     * @return string
     */
    public function getBrowserAttribute()
    {
        return $this->detector()->getClient()['name'] ?? '';
    }

    /**
     * Get browser attribute.
     *
     * @return string
     */
    public function getIsCurrentAttribute()
    {
        return $this->session_id == Session::getId();
    }

    /**
     * Get device attribute
     *
     * @return string mobile|tablet|desktop
     */
    public function getDeviceAttribute()
    {
        $detect = new Mobile_Detect;
        $detect->setUserAgent($this->user_agent);

        if ($detect->isMobile()) {
            return 'mobile';
        }
        if ($detect->isTable()) {
            return 'tablet';
        }
        return 'desktop';
    }
}
