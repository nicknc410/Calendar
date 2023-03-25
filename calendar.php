<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="calendar.css">
    <title>My Calendar Website</title>
  </head>
  <body>
  <script src="calendar.js"></script>
  <div class="nameMonth">
    <?php
        session_start();
        $user=$_SESSION['username'];
        printf("<header class='title'>%s's calendar</header>", $user);    
    ?>
    <header class="currentMonth"></header>
</div>
<form id="logout" action="logout.php" method="post">
    <input type="submit" class="logoutbtn" value="Logout">
</form>
  <script>
        let toggle=false; //variable to hold toggle value
        monthsList=["January", "February", "March", "April", "May", "June", //since some functions return month in an int, we can use a list to hold the months and index them to retrieve
    "July", "August", "September", "October", "November", "December"]
        const date=new Date();
        const tagColor={'Work':'#ffc0cb','Personal':'#badaff','School':'#c7eed4'}; //associative array for each tag
        let month=date.getMonth();
        let year=date.getFullYear();
        function currMonth(){
            document.getElementsByClassName("currentMonth")[0].innerHTML=monthsList[month] +" "+ year; //gets current month
        }
    </script>
    <script src="calendar.js"></script>
    <!-- Below holds the format for the calendar on the website -->
    <div class="calendar">
        <table id="daysMonth">
        <tbody id="wholetable">
        <tr class="DaysOfWeek">
                    <th>Sunday</th>
                    <th>Monday</th>
                    <th>Tuesday</th>
                    <th>Wednesday</th>
                    <th>Thursday</th>
                    <th>Friday</th>
                    <th>Saturday</th>
            </tr>
        </tbody>
        </table>
    </div>
    <!-- Below holds the color key for the calendar representing number of events on each day-->
    <div id="key">
        <p>Calendar Key</p>
        <ul>
            <li>White= No events on day</li>
            <li>Green= 1-3 events on day</li>
            <li>Yellow= 4-6 events on day</li>
            <li>Red= 7 or more events on day</li>
        </ul>
    </div>
    <!-- Buttons to increment of decrement month. &larr and &rarr found on html symbols page: https://www.toptal.com/designers/htmlarrows/-->
    <button id="leftButton">&larr;</button>
    <button id="rightButton">&rarr;</button>
    <script>
        document.addEventListener("DOMContentLoaded", currMonth, false);
        //updates calendar for the current view to represent the month desired
        function updateCalendar(){
            let cur=new Month(year, month,1);
            let weeks = cur.getWeeks();
            for(let w in weeks){
                let days = weeks[w].getDates();
                // days contains normal JavaScript Date objects.
                let idName=String(w);
                document.getElementById('wholetable').innerHTML+="<tr id="+idName+">";
                for(let d in days){
                    //console.log(days[d].getMonth()+"/"+days[d].getDate()+"/"+year);    
                    if (days[d].getMonth()!=month){
                        document.getElementById(idName).innerHTML+="<td class='without'></td>";
                    }
                    else{
                        document.getElementById(idName).innerHTML+="<td><div id='"+(days[d].getMonth()+1)+"/"+days[d].getDate()+"/"+year+"'> <input type='radio' name='date' value='" + (days[d].getMonth()+1)+"/"+days[d].getDate()+"/"+year+"'>"+days[d].getDate()+"</div></td>";
                        const data={"date":(days[d].getMonth()+1)+"/"+days[d].getDate()+"/"+year};
                        fetch("numEvents.php", {
                            method: 'POST',
                            body: JSON.stringify(data),
                            headers: { 'content-type': 'application/json' }
                        })
                        .then(response => response.json())
                        .then(data=>{
                            if (data.success) {
                                document.getElementById((days[d].getMonth()+1)+"/"+days[d].getDate()+"/"+year).style.backgroundColor=data.numEvents;
                            } else {
                                console.log("Error");
                            }
                        })
                        .catch(err => console.error(err));
                    }
                }   
                document.getElementById('wholetable').innerHTML+="</tr>";    
            }
        }
        function leftBtn(){
            month-=1;
            if (month<0){
                month=11;
                year-=1;
            }
            currMonth();
            document.getElementById("wholetable").innerHTML="<tr class='DaysOfWeek'><th>Sunday</th><th>Monday</th><th>Tuesday</th><th>Wednesday</th><th>Thursday</th><th>Friday</th><th>Saturday</th></tr></tbody>"
            updateCalendar();
        }
        function rightBtn(){
            month+=1;
            if (month>11){
                year+=1;
            }
            month=month%12;
            currMonth();
            document.getElementById("wholetable").innerHTML="<tr class='DaysOfWeek'><th>Sunday</th><th>Monday</th><th>Tuesday</th><th>Wednesday</th><th>Thursday</th><th>Friday</th><th>Saturday</th></tr></tbody>"
            updateCalendar();
        }
        document.addEventListener("DOMContentLoaded", updateCalendar, false);
        document.getElementById("leftButton").addEventListener("click",leftBtn,false);
        document.getElementById("rightButton").addEventListener("click",rightBtn,false);
    </script>
    <br>
<script src="colorCalendar.js"></script>
<!-- Holds the buttons and form needed for the current function-->
    <div id="buttons">
    <input type="button" id="viewBtn" value="View Events">
    <input type="submit" id="addEvent" value="Add Event">
    <input type="submit" id="toggleTag" value="Toggle Tag">
    <div id="add">
        Event Name:<input type='text' id='eventName'><br>
        <br>
        <div id="start">
            Start Time:<br>Hours<select name="startHr" id='startHr'> 
            <option value='1'>1</option>
            <option value='2'>2</option>
            <option value='3'>3</option>
            <option value='4'>4</option>
            <option value='5'>5</option>
            <option value='6'>6</option>
            <option value='7'>7</option>
            <option value='8'>8</option>
            <option value='9'>9</option>
            <option value='10'>10</option>
            <option value='11'>11</option>
            <option value='12'>12</option>
        </select>:
            Minutes<select name="startMin" id="startMin">
            <option value='00'>00</option>
            <option value='15'>15</option>
            <option value='30'>30</option>
            <option value='45'>45</option>
            </select>
            Time of day<select name="dayTime" id="dayTime">
                <option value='AM'>AM</option>
                <option value='PM'>PM</option>
                </select><br>
            Tag<select name="tags" id="tagType">
                    <option value="Personal">Personal</option>
                    <option value="School">School</option>
                    <option value="Work">Work</option>
                </select>
    </div>
    <br>
        <input type="submit" id="addEventToDb" value="Add Event">
        <input type="submit" id="cancel" value="Cancel">
    </div>
    <script>
        //check to see which date was clicked and then display the form window or hide it after action is completed
        function clickedAdd(){
            let dateList=document.getElementsByName("date");
            let chosen="";
            for (let i=0;i<dateList.length;i++){
                    if (dateList[i].checked){
                        chosen=dateList[i].value;
                        break;
                    }
                }
            if (chosen==""){
                alert("Please choose a date.");
                return;
            }
            document.getElementById("add").style.display='block';
        }
        function cancel(){
            document.getElementById("add").style.display='none';
        }
        //function corresponding with "Add Event" on the add event form. Sends input responses to database.
        function eventDb(){
            let dateList=document.getElementsByName("date");
            let chosen="";
            for (let i=0;i<dateList.length;i++){
                if (dateList[i].checked){
                    chosen=dateList[i].value;
                    break;
                }
            }
            let tag=document.getElementById("tagType").value;
            let startTime=document.getElementById("startHr").value+":"+document.getElementById("startMin").value+document.getElementById("dayTime").value;
            let eName=document.getElementById("eventName").value;
            if (eName==""){
                alert("Please enter in a event name.");
                return;
            }
            const data={'eventName':eName, 'startTime':startTime,'date':chosen,'tag':tag};
            fetch("addEvent.php", {
                method: 'POST',
                body: JSON.stringify(data),
                headers: { 'content-type': 'application/json' }
            })
            .then(response => response.json())
            .then(data=>{
                if (data.success) {
                    console.log("success");
                    listEvents();
                    color();
                    document.getElementById("add").style.display='none';
                } else {
                    console.log("no");
                }
            })
            .catch(err => console.error(err));
            }
        document.getElementById("addEvent").addEventListener("click", clickedAdd,false);
        document.getElementById("cancel").addEventListener("click", cancel, false);
        document.getElementById("addEventToDb").addEventListener("click",eventDb,false);
    </script>

    </div>
    
    <hr>
    <!-- This div contains the list of our events. Contains some forms and buttons that can be performed on each event-->
    <div id="listOfEvents">
        <h1 id="viewTitle"></h1>
        <div id="functions" style='display:none;'>
            <button id="deleteBtn"> Delete</button>
            <button id="editBtn"> Edit</button>
            <button id="shareBtn">Share</button>
        <div id="editWindow">
                Event Name:<input type='text' id='newName'><br>
                <br>
                <div id="newStart">
                    Start Time:<br>Hours<select name="newSHR" id='newSHR'> 
                    <option value='1'>1</option>
                    <option value='2'>2</option>
                    <option value='3'>3</option>
                    <option value='4'>4</option>
                    <option value='5'>5</option>
                    <option value='6'>6</option>
                    <option value='7'>7</option>
                    <option value='8'>8</option>
                    <option value='9'>9</option>
                    <option value='10'>10</option>
                    <option value='11'>11</option>
                    <option value='12'>12</option>
                </select>:
                    Minutes<select name="newSMin" id="newSMIN">
                    <option value='00'>00</option>
                    <option value='15'>15</option>
                    <option value='30'>30</option>
                    <option value='45'>45</option>
                    </select>
                    Time of day<select name="newDAYTIME" id="newDAYTIME">
                        <option value='AM'>AM</option>
                        <option value='PM'>PM</option>
                        </select><br>
                    Tag<select name="tags" id="edittagType">
                        <option value="Personal">Personal</option>
                        <option value="School">School</option>
                        <option value="Work">Work</option>
                    </select>
            </div>
            <br>
                <input type="submit" id="editEvent" value="Edit Event">
                <input type="submit" id="editCancel" value="Cancel">
    </div>
    <!-- Share window is hidden until a user prompts that they want to edit an event-->
    <div id="shareWindow" style='display:none;'>
        Share with:<input type="text" id="otherUsers">
        <br>
        <input type="submit" id="shareEvent" value="Share Event">
        <input type="submit" id="shareCancel" value="Cancel">
    </div>
</div>
        <br>
        <br>

        <div id="eventsList">

        </div>
    </div>
    <script>
        //if a user wants to cancel a process, they can click the respective 'cancel' button to get rid of the window
        function cancelEdit(){
            document.getElementById("editWindow").style.display="none";
        }
        function cancelShare(){
            document.getElementById("shareWindow").style.display="none";
        }
        document.getElementById("shareCancel").addEventListener("click",cancelShare,false);
        document.getElementById("editCancel").addEventListener("click",cancelEdit,false);
        //function share() checks to see if a user chose a date and whether that user is the owner of that event
        function share(){
            let events=document.getElementsByName("choice");
            let eventId="";
            let owner="";
            for (let i=0;i<events.length;i++){
                if (events[i].checked){
                    eventId=events[i].value;
                    owner=events[i].id;
                    break;
                }
            }
            if (eventId==""){
                alert("Choose event to share.");
            }
            else{
                if (owner=="0"){
                    alert("You do not have ownership and therefore cannot share");
                }
                else{
                    document.getElementById("shareWindow").style.display="block";
                    }
            }
        }
        //sends event to database confirming everything from function share()
        function shareButtonFunction(){
            let events=document.getElementsByName("choice");
            let eventId="";
            for (let i=0;i<events.length;i++){
                if (events[i].checked){
                    eventId=events[i].value;
                    break;
                }
            }
            let user=document.getElementById("otherUsers").value;
            const data={'user':user, 'eventId':eventId};
            fetch("share.php", {
                    method: 'POST',
                    body: JSON.stringify(data),
                    headers: { 'content-type': 'application/json' }
                })
                .then(response => response.json())
                .then(data => {
                if(data.success){
                    document.getElementById("shareWindow").style.display="none";
                    console.log("Shared");
                }
                else{
                    alert(data.message);
                }
                })
                .catch(err => console.error(err));
        }
        //deletes event after confirming that the user chose a date.
        function deleteEvent(){
            let events=document.getElementsByName("choice");
            let eventId=""
            for (let i=0;i<events.length;i++){
                if (events[i].checked){
                    eventId=events[i].value;
                    break;
                }
            }
            if (eventId==""){
                alert("Choose event to delete.");
            }
            else{
                const data={"eventId":eventId};
                fetch("delete.php", {
                    method: 'POST',
                    body: JSON.stringify(data),
                    headers: { 'content-type': 'application/json' }
                })
                .then(response => response.json())
                .then(data => {
                if(data.success){
                    console.log("Success");
                    listEvents();
                    color();
                }
                else{
                    console.log("Error:Deletion failed");
                }
                })
                .catch(err => console.error(err));
            }
        }
        //similarly to the share function, this function checks to see if the user inputted in a choice for date and whether they are owner.
        function editEvent(){
            let events=document.getElementsByName("choice");
            let eventId="";
            let owner="";
            for (let i=0;i<events.length;i++){
                if (events[i].checked){
                    eventId=events[i].value;
                    owner=events[i].id;
                    break;
                }
            }
            if (eventId==""){
                alert("Choose event to edit.");
            }
            else{
                if (owner=="0"){
                    alert("You do not have ownership and therefore cannot edit");
                }
                else{
                    document.getElementById("editWindow").style.display="block";
                    }
            }
        }
        //function to send changes to database after confirming input in editEvent()
        function editButtonFunction(){
            let events=document.getElementsByName("choice");
            let eventId="";
            for (let i=0;i<events.length;i++){
                if (events[i].checked){
                    eventId=events[i].value;
                    break;
                }
            }
            let newName=document.getElementById("newName").value;
            let startTime=document.getElementById("newSHR").value+":"+document.getElementById("newSMIN").value+document.getElementById("newDAYTIME").value;
            let tag=document.getElementById("edittagType").value;
    
            const data={"newName": newName, 'start':startTime,'eventId':eventId,'tag':tag};
            fetch('edit.php',{
                method:'POST',
                body:JSON.stringify(data),
                headers: {'content-type': 'application/json'}
            })
            .then(response=>response.json())
            .then(data=>{
                if (data.success){
                    listEvents();
                    document.getElementById("editWindow").style.display="none";
                }
                else{
                    console.log("Edit not possible");
                }
            })
            .catch(err=>console.log("not"));
        }
        //toggles button on and off, switches the value of the toggle variable between false and true.
        function toggleTagButton(){
            toggle=!toggle;
            listEvents();
        }
        //listEvents() gets rows from the database associated with the user AND date then displayes it on the page.
        function listEvents(){
            let dateList=document.getElementsByName("date");
            let chosen="";
            document.getElementById("eventsList").innerHTML="";
            for (let i=0;i<dateList.length;i++){ //checks to see if user chose date.
                if (dateList[i].checked){
                    chosen=dateList[i].value;
                    break;
                }
            }
            if (chosen==""){
                alert("Please choose a date.");
            }
            else{
                document.getElementById("functions").style.display="block";
                const data={'date':chosen};
                fetch("viewEvents.php", {
                method: 'POST',
                body: JSON.stringify(data),
                headers: { 'content-type': 'application/json' }
                })
                .then(response => response.json())
                .then(data=>{ //data returns a list of associatve arrays from a SQL query
                    if (data.success) {
                        document.getElementById("viewTitle").innerHTML="Events on "+chosen;
                        //displays each event
                        for (let i in data.events){
                            let formatEventStart=parseInt(data.events[i].startTime.slice(0,3));
                            let eventStart="";
                            if (formatEventStart>=12){//formatting to ensure we get correct time (non-military)
                                eventStart=formatEventStart%12+":"+data.events[i].startTime.slice(3)+"PM";
                            }
                            else{
                                eventStart=formatEventStart+":"+data.events[i].startTime.slice(3)+"AM";
                            }
                            //document.getElementById("eventsList").innerHTML+="<input type='radio' name='choice' value='"+data.events[i].eventId+"'><strong>"+data.events[i].eventName+"</strong><br>\nStart:<p>"+eventStart+"</p>\nEnd:<p>"+eventEnd+"</p></div>";
                            let eventItem=document.createElement("input");
                            eventItem.type='radio';
                            eventItem.name='choice';
                            eventItem.value=data.events[i].eventId;
                            eventItem.id=data.events[i].owner;
                            //eventItem.innerHTML="<strong>"+data.events[i].eventName+"</strong><br>\nStart:<p>"+eventStart+"</p>\nEnd:<p>"+eventEnd+"</p>";
                            document.getElementById("eventsList").append(eventItem);
                            if (toggle){ //if toggle is true, we display the color.
                                document.getElementById("eventsList").innerHTML+="<strong>"+data.events[i].eventName+"<div style='background-color:"+tagColor[data.events[i].tag]+";'></strong><br>\nStart:<p>"+eventStart+"</p>\nTag:<p>"+data.events[i].tag+"</p><div>";
                            }
                            else{
                                document.getElementById("eventsList").innerHTML+="<strong>"+data.events[i].eventName+"<div></strong><br>\nStart:<p>"+eventStart+"</p>\nTag:<p><em>"+data.events[i].tag+"</em></p><div>";
                            }
                        }
                    } else {
                        console.log("no");
                    }
                })
                .catch(err => console.error(err));
                }
        }
    //adding eventlisteners for each button
     document.getElementById("viewBtn").addEventListener("click", listEvents,false);
     document.getElementById("deleteBtn").addEventListener("click",deleteEvent,false);
     document.getElementById("editBtn").addEventListener("click",editEvent,false);
     document.getElementById("editEvent").addEventListener("click",editButtonFunction,false);
     document.getElementById("shareBtn").addEventListener("click",share,false);
     document.getElementById("shareEvent").addEventListener("click",shareButtonFunction,false);
     document.getElementById("toggleTag").addEventListener("click",toggleTagButton,false);
     </script>
    
  </body>
</html>