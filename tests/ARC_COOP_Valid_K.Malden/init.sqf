["ARC_navalBarrage", {
    params ["_unit", "_target"];
    _unit setVehicleAmmo 1;
    _unit doSuppressiveFire _target;
}] call CBA_fnc_addEventHandler;
