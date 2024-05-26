<?php

namespace Models\Enumerations;

enum TypeOfGuns: string
{
    case Pistol = 'Pistol';
    case Rifle = 'Rifle';
    case Shotgun = 'Shotgun';
    case Sniper = 'Sniper';
    case MachineGun = 'Machine Gun';
    case SubmachineGun = 'Submachine Gun';
    case GrenadeLauncher = 'Grenade Launcher';
    case RocketLauncher = 'Rocket Launcher';
    case Flamethrower = 'Flamethrower';
    case Minigun = 'Minigun';
    case Other = 'Other';
}




