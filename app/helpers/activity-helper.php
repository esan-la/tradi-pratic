<?php

if (!function_exists('activity')) {
    /**
     * Helper activity() pour compatibilité
     * Si le package Spatie Activity Log n'est pas installé,
     * retourne un objet mock qui ne fait rien
     */
    function activity(?string $log = null)
    {
        // Si le package Spatie est installé, l'utiliser
        if (class_exists(\Spatie\Activitylog\ActivityLogger::class)) {
            return app(\Spatie\Activitylog\ActivityLogger::class);
        }

        // Sinon, retourner un mock qui ne fait rien
        return new class {
            public function performedOn($model) {
                return $this;
            }

            public function causedBy($user) {
                return $this;
            }

            public function withProperties($properties) {
                return $this;
            }

            public function log($description) {
                // Ne rien faire - logging désactivé
                return null;
            }

            public function useLog($logName) {
                return $this;
            }

            public function on($model) {
                return $this;
            }

            public function by($user) {
                return $this;
            }

            public function event($event) {
                return $this;
            }
        };
    }
}
