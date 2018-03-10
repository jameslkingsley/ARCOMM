["arcore_briefing_ended", {
    [{
        {
            private _target = selectRandom [
                barrageTarget1,
                barrageTarget2,
                barrageTarget3,
                barrageTarget4,
                barrageTarget5,
                barrageTarget6,
                barrageTarget7,
                barrageTarget8,
                barrageTarget9
            ];

            ["ARC_navalBarrage", [_x, _target], _x] call CBA_fnc_targetEvent;
        } forEach [
            ARC_navalBarrage1,
            ARC_navalBarrage2
        ];
    }, 30] call CBA_fnc_addPerFrameHandler;
}] call CBA_fnc_addEventHandler;
