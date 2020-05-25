// If you want to base a loadout on an existing one, this repository contains them all:
// https://github.com/ARCOMM/arc_misc/tree/master/addons/tmf_loadouts

class baseMan {// Weaponless baseclass
	displayName = "Unarmed";
	// All randomized.
	uniform[] = {
		"CUP_U_B_US_BDU_OD",
		"CUP_U_B_BDUv2_roll_gloves_dirty_OD",
		"CUP_U_B_BDUv2_roll_dirty_OD",
		"CUP_U_B_BDUv2_roll2_gloves_dirty_OD",
		"CUP_U_B_BDUv2_roll2_dirty_OD",
		"CUP_U_B_BDUv2_gloves_dirty_OD",
		"CUP_U_B_BDUv2_dirty_OD"
	};
	vest[] = {"CUP_V_O_SLA_M23_1_OD"};

	backpack[] = {"CUP_B_AlicePack_OD"};
	headgear[] = {
		"CUP_H_SLA_Helmet_URB",
		"CUP_H_SLA_Helmet_URB_worn"
	};
	goggles[] = {};
	faces[] = {"Default"};

	// These are added to the uniform or vest
	magazines[] = {
		LIST_2("CUP_HandGrenade_RGD5"),
		LIST_2("SmokeShell")
	};
	items[] = {
		MEDICAL_R,
		"ACE_Fortify",
		"ACE_Flashlight_XL50"
	};
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
		LIST_8("CUP_30Rnd_762x39_AK47_M")
	};
};
class cls : r
{
	displayName = "Combat Life Saver";
	traits[] = {"medic"};
	backpackItems[] = {MEDICAL_CLS};
};
class m : r
{
	displayName = "Medic";
	traits[] = {"medic"};
	backpackItems[] = {MEDICAL_M};
};
class ftl : r
{
	displayName = "Fireteam Leader";
	primaryWeapon[] = {"CUP_arifle_AK47_GL"};
	backpackItems[] =
	{
		LIST_6("CUP_1Rnd_SmokeGreen_GP25_M"),
		LIST_4("CUP_IlumFlareWhite_GP25_M"),
		LIST_2("CUP_IlumFlareWhite_GP25_M"),
		LIST_2("CUP_IlumFlareWhite_GP25_M")
	};
	items[] += {"ACE_MapTools"};
	linkedItems[] += {"Binocular"};
};
class sl : ftl
{
	displayName = "Squad Leader";
};
class co : ftl
{
	displayName = "Platoon Leader";
};
class ar : baseMan
{
	displayName = "Automatic Rifleman";
	primaryWeapon[] = {"CUP_arifle_RPK74"};
	magazines[] =
	{
		LIST_3("CUP_75Rnd_TE4_LRT4_Green_Tracer_762x39_RPK_M")
	};
	backpackItems[] =
	{
		LIST_6("CUP_75Rnd_TE4_LRT4_Green_Tracer_762x39_RPK_M")
	};	
};
class aar : r
{
	displayName = "Assistant Automatic Rifleman";
	backpackItems[] =
	{
		LIST_6("CUP_75Rnd_TE4_LRT4_Green_Tracer_762x39_RPK_M")
	};
	linkedItems[] += {"Binocular"};
};
class rat : r
{
	displayName = "Rifleman (AT)";
	secondaryWeapon[] = {"CUP_launch_RPG18"};
};
class mtrg : r
{
	displayName = "Mortar Gunner";
	backPack[] = {"I_Mortar_01_weapon_F"};
	items[] += {"ACE_RangeTable_82mm","ACE_MapTools"};
};
class mtrac : r
{
	displayName = "Mortar Ammo Carrier";
	backPack[] = {"I_Mortar_01_weapon_F"};
	items[] += {"ACE_RangeTable_82mm","ACE_MapTools"};
};
class mtrtl : ftl
{
	displayName = "Mortar Team Leader";
	backPack[] = {"B_Mortar_01_support_F"};
	backpackItems[] = {};	
	items[] += {"ACE_RangeTable_82mm"};
};
class matg : r
{
	displayName = "MAT Gunner";
	backpack[] = {"CUP_B_RPGPack_Khaki"};
	secondaryWeapon[] = {"CUP_launch_RPG7V"};
	secondaryAttachments[] = {"CUP_optic_PGO7V"};
	magazines[] +=
	{
		LIST_3("CUP_PG7VM_M")
	};
};
class matac : r
{
	displayName = "MAT Ammo Carrier";
	backpack[] = {"CUP_B_RPGPack_Khaki"};
	backpackItems[] +=
	{
		LIST_2("CUP_PG7VM_M"),
		LIST_2("CUP_OG7_M")
	};
};
class mattl : ftl
{
	displayName = "MAT Team Leader";
};