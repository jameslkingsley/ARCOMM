// If you want to base a loadout on an existing one, this repository contains them all:
// https://github.com/ARCOMM/arc_misc/tree/master/addons/tmf_loadouts

class baseMan {// Weaponless baseclass
	displayName = "Unarmed";
	// All randomized.
	uniform[] = {"CUP_U_O_RUS_Flora_1"};
	vest[] = {"CUP_V_RUS_Smersh_1", 
		"CUP_V_O_Ins_Carrier_Rig_Light",
		"CUP_V_O_Ins_Carrier_Rig_MG"
	};

	backpack[] = {"CUP_B_AlicePack_Khaki"};
	headgear[] = {"CUP_H_TK_Helmet"};
	goggles[] = {"default"};
	faces[] = {"Default"};

	// These are added to the uniform or vest
	magazines[] = {LIST_2("SmokeShell")};
	items[] = {MEDICAL_R};
	// These are added directly into their respective slots
	linkedItems[] = {
		"ItemMap",
		"ItemCompass",
		"ItemRadio",
		"ItemWatch"
	};
};
class r : baseMan
{
	displayName = "Rifleman";
	primaryWeapon[] = {"CUP_arifle_AK47_Early"};
	magazines[] += {
		LIST_5("CUP_30Rnd_762x39_AK47_M")
	};
};
class ftl : r
{
	displayName = "Fireteam Leader";
	primaryWeapon[] = {"CUP_arifle_AK47_GL"};
	magazines[] += {"CUP_HandGrenade_RGD5"};
	backpackItems[] = {LIST_3("CUP_IlumFlareRed_GP25_M")};
	linkedItems[] += {"Binocular"};
};
class ar : baseMan
{
	displayName = "Automatic Rifleman";
	primaryWeapon[] = {"CUP_lmg_PKM"};
	magazines[] =
	{
		LIST_3("CUP_100Rnd_TE4_LRT4_762x54_PK_Tracer_Red_M")
	};
	backpackItems[] =
	{
		LIST_2("CUP_100Rnd_TE4_LRT4_762x54_PK_Tracer_Red_M")
	};	
};
class rat : r
{
	displayName = "Rifleman (AT)";
	secondaryWeapon[] = {"CUP_launch_RPG18"};
};