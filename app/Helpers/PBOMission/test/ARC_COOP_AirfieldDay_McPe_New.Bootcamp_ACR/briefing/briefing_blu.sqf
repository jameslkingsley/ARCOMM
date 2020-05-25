/* ===============================================
    GENERAL BRIEFING NOTES
     - Uses HTML style syntax. All supported tags can be found here - https://community.bistudio.com/wiki/createDiaryRecord
     - For images use <img image='FILE'></img> (for those familiar with HTML note it is image rather than src).
     - Note that using the " character inside the briefing block is forbidden use ' instead of ".
*/

/* ===============================================
    SITUATION
     - Outline of what is going on, where we are we and what has happened before the mission has started? This needs to contain any relevant background information.
     - Draw attention to friendly and enemy forces in the area. The commander will make important decisions based off this information.
     - Outline present weather conditions, players will typically assume that it is daylight with sunny weather.
*/

private _situation = ["diary", ["Situation","
<br/>
11th of October 1967<br/> 
As you all know the Soviets didn't react kindly to our declaration of independence. Unfortunately the reaction from NATO hasn't been as strong as we had hoped.<br/>
So as of last night we are at war. We knew this was comming and have prepared accordingly. All of the aircraft have been moved off-site and are safe for now.<br/>
The latest news is that Marshal Grechko has tasked at least two divisions of the Red Army to our region. A sizeable portion of these troops will no doubt be assigned to capture this airfield.<br/>
We've also been told that the VDV paratroopers are involved. Good thing we have the radar still working it will be able to spot any aircraft heading in our direction.<br/>
Anyway high' command's plan is to hold out for a NATO reaction. Gods willing it will be in our favour. Until then we hold.<br/> 
<br/><br/>
<font size='18'>ENEMY FORCES</font>
<br/>
The Red Army.<br/> 
We have a motorised division to our south-west and we've received word that the Soviets are dropping paratroopers behind our lines.<br/> 
We should be fairly safe from the north and north-west. The forest will hamper any vehicle movement and paratroopers won't be able to land there.<br/> 
<br/><br/>

<font size='18'>FRIENDLY FORCES</font>
<br/>
2 Platoons of national guards.<br/> 
Attached to the platoon is a mortar unit and a mobile AT team."]];
/* ===============================================
    MISSION
     - Describe any objectives that the team is expected to complete.
     - Summarize(!) the overall task. This MUST be short and clear.
*/

private _mission = ["diary", ["Mission","
<br/>
Defend the airfield at any cost.<br/> 
If your defence fails ensure the following:<br/> 
Make sure to destroy the radar installation at the communications building.<br/> 
Destroy the fuel depot.<br/> 
Destroy as much munitions as you are able.<br/> 
Try to disable to the air control tower.<br/> 
"]];

/* ===============================================
    EXECUTION
     - Provide an outline as to what the commander of the player's command might give.
*/

private _execution = ["diary", ["Execution","
<br/>
<font size='18'>COMMANDER'S INTENT</font>
<br/>
Position yourselves wisely. The airfield is large and you can't hope to cover all sectors with your limited numbers.<br/> 
Make use of whatever you can find at the airbase. Make sure to take stock of the munitions depot. 
<br/><br/>

<font size='18'>MOVEMENT PLAN</font>
<br/>
To the south-east of the airfield is CAMP 131. Under no circumstances are you allowed near or inside the camp. What goes on there is way above our paygrade. The guards shoot first and ask questions later. 
<br/><br/>

<font size='18'>FIRE SUPPORT PLAN</font>
<br/>
Avoid firing mortar barrages into villages and the like. Keep damage to airfield structures to a minimum unless you retreat from the airfield. 
<br/><br/>

<font size='18'>SPECIAL TASKS</font>
<br/>
The comms building has a working radar installation. As long as it is functioning it will provide you with warnings of incoming air contacts. 
"]];

/* ===============================================
    ADMINISTRATION
     - Outline of logistics: available resources (equipment/vehicles) and ideally a summary of their capabilities.
     - Outline of how to use any mission specific features/scripts.
     - Seating capacities of each vehicle available for use.
*/

private _administration = ["diary", ["Administration","
<br/>
 There's a lot of small arms and ammo available over at the munitions bunkers. You can also find explosives at the munitions depot.<br/>  
 All vehicles munitions and heavy weapons at the airfield are at our disposal. 
"]];

player createDiaryRecord _administration;
player createDiaryRecord _execution;
player createDiaryRecord _mission;
player createDiaryRecord _situation;