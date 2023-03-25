function color(){
    let cur=new Month(year, month,1);
    let weeks = cur.getWeeks();
    for(let w in weeks){
        let days = weeks[w].getDates();
        let idName=String(w);
        for(let d in days){
            if (days[d].getMonth()==month){
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
    }
}