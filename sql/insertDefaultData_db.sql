USE WorldOfGuns_db;

INSERT INTO Users (username, password, email, avatarId, admin)
VALUES
    ('Alehandro34', '7a6714b628539fae9a537e7cd76d9dc6f37c253a4c28f1418dccf018df100d7c', 'John@sadada', 1,0), -- password: lol24
    ('BossDeBoss', 'da2d8bd7c184013ef6e93053c861ecaa847edd055974d6e49258650ef7a5abfe', 'Jane@adfsdf', 2,1); -- password: lol23 

INSERT INTO Modification (modificationName, modificationImagePath, modificationDescription, modificationEstimatedPrice)
VALUES
    ('Sub-compact HS407K-X2 Red Dot Sights','/images/modifications/mod 1.jpg','The sub-compact pistol sized Holosun HS407K-X2 Red Dot Sight provides a large field of view paired with useful features that will help you stay on target while greatly reducing target acquisition time. Made for carry pistols, this optic offers a Lock Mode that prevents unintentional button presses while the optic is holstered and a side access battery tray, which allows the operator to change the battery without removing the optic from the firearm.',224.99),
    ('Sig Sauer 9/.357 FT Bullseye Fiber & Tritium Night Sights','/images/modifications/mod 2.jpg','MEPROLIGHT combat-proven self-illuminated night sights enable you to hit stationary or moving targets under low-light conditions with dramatically increased hit probability. Designed as replacement parts for the standard weapon sights, MEPROLIGHT Self-Illuminated night sights can be mounted directly with minor modifications.',140.99), 
    ('Electro-Optics SOT65114 Tango6 5-30x 56mm Obj 18.90-3.30 ft','/images/modifications/mod 3.jpg','Sig Sauer TANGO6 Rifle Scope, 5-30X56MM, 34MM, FFP, DEV-L-MRAD Illuminated Reticle, 0.1 MRAD Adjustments, Matte Finish, Black Color, LevelPlex Anti-Cant System SOT65114',2249.99), 
    ('RomeoZero OD Green P365XL Grip Module Kit 3MOA','/images/modifications/mod 4.jpg','SpecraCoat HD Polymer Lens with 10 times the impact resistance over traditional glass lenses Ultralite Polymer housing with stippling pattern that matches the P365XL grip, for an integrated look',190), 
    ('Critical Defense 25 ACP 35 gr Flex Tip eXpanding 25 Bx/ 10 Cs','/images/modifications/mod 5.jpg','Hornady Critical Defense, 25 ACP, 35 Grain, FlexTip, 25 Round Box 90014',26), 
    ('Grip Mod 2 AR-15 Black Polymer','/images/modifications/mod 6.jpg','Bravo Company BCMGUNFIGHTER Grip Mod 2, Fits AR Rifles, Black BCM-GFG-MOD-2-BLACK',24.76), 
    ('Manual Safety Grip Module','/images/modifications/mod 7.jpg','Sig Sauer P365XL Grip Module, Manual Safety, Coyote. This polymer grip module is a Sig Sauer factory original replacement. This grip module kit comes with the magazine release, magazine release spring, and magazine release stop installed in the grip module along with the manual safety cutout. ',69.69), 
    ('Glock Gen 4 417 Dual Port Compensator','/images/modifications/mod 8.jpg','he 417 Compensator features a 2 chamber design (2 vertical ports and 2 side venting ports), it also features a front sight hole. The 417 Single Port Compensator features a single top venting port and it also features a front sight hole. ',84.99), 
    ('BANISH 30 Suppressor','/images/modifications/mod 9.jpg','he 417 Compensator features a 2 chamber design (2 vertical ports and 2 side venting ports), it also features a front sight hole. The 417 Single Port Compensator features a single top venting port and it also features a front sight hole. ',999.99),   
    ('Bayonet Knife Ak-47','/images/modifications/mod 10.webp','The AK47 assault rifle was introduced during a period in history when the bayonets future was in debate. In the decade following the outbreak of the Second World War, most of the major powers produced an infantry rifle without the ability to mount a bayonet. Without a single exception, every one was redesigned to accept a bayonet or or replaced with a rifle that did within a few years of its introduction.',10.99); 



INSERT INTO Guns (userId, gunName, gunDescription, countryOfOrigin, year, gunEstimatedPrice, type, gunImagePath, soundPath, showInGunsPage)
VALUES
    (2,'Type 74 Flamethrower','The Type 74 is man portable flamethrower of Chinese origin. The design is very similar to the Soviet LPO-50. The Type 74 was developed to increase effectiveness while reducing the weight at the same time.','China',1970,1000.00,'Flamethrower','/images/guns/g10.jpg','/sounds/weapons_sounds/g10s.mp3',1),
    (2,'Desert Eagle','The Desert Eagle is a gas-operated, semi-automatic pistol known for chambering the .50 Action Express, the largest centerfire cartridge of any magazine-fed, self-loading pistol.','Israel',1983,800,'Pistol','/images/guns/g2.jpg','/sounds/weapons_sounds/g2s.mp3',1),
    (2,'M1911','The Colt M1911 (also known as 1911, Colt 1911 or Colt Government in the case of Colt-produced models) is a single-action, recoil-operated, semi-automatic pistol chambered for the .45 ACP cartridge.','USA',1911,560,'Pistol','/images/guns/g1.jpg','/sounds/weapons_sounds/g1s.mp3',1),
    (2,'M16','The M16 rifle (officially designated Rifle, Caliber 5.56 mm, M16) is a family of military rifles adapted from the ArmaLite AR-15 rifle for the United States military. The original M16 rifle was a 5.56x45mm automatic rifle with a 20-round magazine.','USA',1959,2500,'Rifle','/images/guns/g3.webp','/sounds/weapons_sounds/g3s.mp3',1),
    (2,'Pump Action Compact Black','Gauge 12 GA,Action,Extra Short 3" Travel Pump Action with Geometric Glide Forend System ,Magazine ,4+1 2¾" or 3+1 3" Shell with Integrated Sling Swivel Mounts, Choke, Interchangeable MaraPro Chokes C, M, F ,Rib none Recoil, Pad Sculpted Microcell, Polyurethane','USA',2015,399.00,'Shotgun','/images/guns/g4.jpg','/sounds/weapons_sounds/g4s.mp3',1),
    (2,'Thompson','The Thompson submachine gun (also known as the "Tommy gun", "Chicago typewriter", "Chicago piano", "trench sweeper", or "trench broom") is a blowback-operated, selective-fire submachine gun, invented and developed by United States Army Brigadier General John T. Thompson in 1918. ','USA',1917,9999,'Submachine Gun','/images/guns/g7.jpg','/sounds/weapons_sounds/g7s.mp3',1),
    (2,'M60','The M60 is a belt-fed machine gun that fires the 7.62x51mm NATO cartridge (similar to .308 Winchester), which is commonly used in larger rifles, such as the M14.','USA',1957,3222.50,'Machine Gun','/images/guns/g6.jpg','/sounds/weapons_sounds/g6s.mp3',1),
    (2,'SV-98','The SV-98 (Snaiperskaya Vintovka Model 1998) is a Russian bolt-action sniper rifle designed by Vladimir Stronskiy.','Russia',1998,4222.50,'Sniper','/images/guns/g5.jpg','/sounds/weapons_sounds/g5s.mp3',1),
    (2,'RPG-7V2','RPG-7V2 is an extremely cost-effective and efficient weapon against all types of heavy and light armoured vehicles, fortifications and enemy manpower.','Russia',1985,2400.90,'Rocket Launcher','/images/guns/g9.jpg','/sounds/weapons_sounds/g9s.mp3',1),
    (2,'M79','Its distinctive report has earned it colorful nicknames, such as "Thumper", "Thump-Gun", "Bloop Tube", "Big Ed", "Elephant Gun", and "Blooper" among American soldiers[5] as well as "Can Cannon" in reference to the grenade size.','USA',1961,2500,'Grenade Launcher','/images/guns/g8.jpg','/sounds/weapons_sounds/g8s.mp3',1),
    (2,'M134','The M134 Minigun is a 7.62 mm, six-barreled machine gun with a high rate of fire (2,000 to 6,000 rounds per minute). It features Gatling-style rotating barrels with an external power source, normally an electric motor.','USA',1970,5550,'Minigun','/images/guns/g11.webp','/sounds/weapons_sounds/g11s.mp3',1),
    (2,'Ak-47 Gold','Ak-47 but Gold, only for the richest.','Saudi Arabia',2012,1000000,'Rifle','/images/guns/g13.jpg','/sounds/weapons_sounds/g13s.mp3',1),
    (2,'Miku-Knife','Weird Stuff','Uknown',1950,100,'Other','/images/guns/g12.webp','/sounds/weapons_sounds/g12s.mp3',1);


INSERT INTO QuestionAndAnswer (question, answer)
VALUES
    ('What is the best gun for self-defense?','The best gun for self-defense is the one you are most comfortable with. It is important to practice with your gun and to be familiar with it.'),
    ('What is the best gun for home defense?','The best gun for home defense is the one you are most comfortable with. It is important to practice with your gun and to be familiar with it.'),
    ('What is the best gun for hunting?','The best gun for hunting depends on the type of game you are hunting. For small game, a .22 caliber rifle is a good choice. For larger game, a shotgun or a rifle with a larger caliber is recommended.'),
    ('What is the best gun for target shooting?','The best gun for target shooting depends on the type of target shooting you are doing. For precision shooting, a rifle with a scope is recommended. For action shooting, a pistol or a shotgun is a good choice.'),
    ('Will USA invade us?','YES'),
    ('Where can I shoot a cow with a bazooka?','Cambodia'),
    ('How much?','1k'),
    ('What is the best gun for concealed carry?','The best gun for concealed carry is the one you are most comfortable with. It is important to practice with your gun and to be familiar with it.'); 


INSERT INTO Favourite (userId, gunId)
VALUES
    (1,1),
    (1,2),
    (1,3),
    (1,4),
    (1,5),
    (1,6),
    (1,7),
    (1,8),
    (2,1);