<?php

namespace Ignite\Chart\Loader\Concerns;

trait HasGoal
{
    /**
     * Get daily goal.
     *
     * @return int|null
     */
    protected function getDailyGoal()
    {
        if ($this->shoudDailyGoalBeShown()) {
            return $this->config->dailyGoal;
        }
    }

    /**
     * Determine if daily goal should be shown.
     *
     * @return bool
     */
    protected function shoudDailyGoalBeShown()
    {
        return $this->getShowGoal();
    }

    /**
     * Get show goal config.
     *
     * @return array
     */
    protected function getShowGoalConfig()
    {
        return [
            'last24hours' => false,
            'today'       => false,
            'yesterday'   => false,
            'last7days'   => true,
            'thisweek'    => true,
            'last30days'  => true,
            'thismonth'   => true,
            'thisyear'    => false,
        ];
    }
}
