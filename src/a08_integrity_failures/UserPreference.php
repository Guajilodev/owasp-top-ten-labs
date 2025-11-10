<?php

class UserPreference {
    public $username;
    public $is_admin = false; // Por defecto, no es admin

    public function __construct($username) {
        $this->username = $username;
    }

    // En un escenario real, un método mágico como __wakeup() o __destruct()
    // podría ser explotado para ejecutar código arbitrario si la clase
    // contiene lógica vulnerable. Para este lab, nos enfocaremos en la
    // modificación de propiedades.
}

?>