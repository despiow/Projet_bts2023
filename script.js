month_year_element = document.getElementById("month_year");
previous_month_element = document.getElementById("previous_month");
next_month_element = document.getElementById("next_month");
weeks_element = document.getElementById("weeks");
modal_el = document.getElementById("availabilities");
modal_close_button = document.getElementById("close_modal")
modal = new Modal(modal_el,{
    // Set the modal to close when the backdrop is clicked
    closeOnBackdropClick: true,
    // Set the modal to close when the escape key is pressed
    closeOnEscapeKey: true
});
modal_close_button.addEventListener('click', () => {
    modal.hide();
});
month_year = new Date();
dayNames_element = document.getElementById("dayNames");
dayNames = ["Lundi", "Mardi", "Mercredi", "Jeudi", "Vendredi"];
dayNames.forEach(function(dayName) /*for each pour chaque éléments +pensé au e en maj ^^ '*/
{
    dayName_element = document.createElement("span");
    dayName_element.classList.add("dayName","select-none","px-10","border-2","rounded-lg","w-14","h-5","flex","items-center","justify-center","border-red-600","text-amber-700","shadow-xl");
    dayName_element.innerHTML = dayName;
    dayNames_element.appendChild(dayName_element);
});
function getWeeks(year, month) 
{
    month_year = new Date(year, month);
    month_year_element.innerHTML = month_year.toLocaleString("fr-FR", { month: "long" }) + " " + month_year.getFullYear();
    month_year_element.classList.add("text-2xl","font-bold","text-center","pb-2","select-none");
    weeks = [];
    firstDate = new Date(year, month, 1);
    lastDate = new Date(year, month + 1, 0);
    numDays = lastDate.getDate();
    // skip days before the first Monday
    startDay = firstDate.getDay();
    if (startDay === 0) startDay = 7;
    if (startDay > 1) 
    {
        firstDate.setDate(firstDate.getDate() - (startDay - 1));
    }
    // iterate the days of the month
    curDate = firstDate;
    while (curDate <= lastDate)
    {
        // iterate the days of the week
        var week = [];
        for (var i = 0; i < 5; i++) {
            week.push(new Date(curDate));
            curDate.setDate(curDate.getDate() + 1);
        }
        curDate.setDate(curDate.getDate() + 2);
        // only add the week if it contains a day from the requested month
        if (week.some(day => day.getMonth() === month)) {
            weeks.push(week);
        }
    }
    return weeks;
}

function generateCalendar(year, month)
{
    weeks_element.innerHTML = "";
    weeks = getWeeks(year, month);
    weeks.forEach(function(week)
    {
        week_element = document.createElement("div");
        week_element.classList.add("week","flex","justify-between","font-medium","text-sm","pb-2");
        week.forEach(function(day) {
            day_element = document.createElement("span");
            day_element.classList.add("day","px-10","w-14","flex","justify-center","items-center");
            day_element.innerHTML = day.getDate();
            if (day.getMonth() !== month) {
                day_element.classList.add("different_month","px-10","w-14","flex","justify-center","items-center","text-gray-400");
            }else{
                day_element.classList.add("border-2","border-amber-400","rounded-lg","text-amber-500","hover:border-red-500","hover:text-red-300","cursor-pointer");
                day_element.addEventListener("click", function()
                {
                    //format date to dd/mm/yyyy
                    var dd = day.getDate();
                    var mm = day.getMonth() + 1;
                    var yyyy = day.getFullYear();
                    if (dd < 10) {
                        dd = '0' + dd;
                    }
                    if (mm < 10) {
                        mm = '0' + mm;
                    }
                    day_formatted = dd + '/' + mm + '/' + yyyy;
                    console.log(day_formatted)
                    //send date to php
                    xhr = new XMLHttpRequest();
                    xhr.open("GET", "script.php", true);
                    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                    xhr.send("date=" + day_formatted);
                    xhr.onreadystatechange = function()
                    {
                        if (xhr.readyState == 4 && xhr.status == 200) 
                        {
                            response = xhr.responseText;
                            //foreach hour of data, create an li element in the "hours" ul
                            data = Object.values(JSON.parse(response)) //transformer le json 
                            hours_element = document.getElementById("hours");
                            if(hours_element){
                                hours_element.innerHTML = "";
                                data.forEach((value,key) => {
                                    li_element = document.createElement("li");
                                    button_element = document.createElement("button");
                                    button_element.classList.add("flex","items-center","p-3","text-base","font-bold","text-gray-900","rounded-lg","group","dark:bg-gray-600","dark:text-white")
                                    if(value == 0){
                                        button_element.classList.add("bg-gray-50","hover:bg-gray-100","dark:hover:bg-gray-500","hover:shadow");
                                    }else{
                                        button_element.classList.add("disabled","cursor-not-allowed");
                                        button_element.disabled = true;
                                    }
                                    button_element.type = "button"
                                    span1_element = document.createElement("span");
                                    span1_element.classList.add("flex-1","ml-3","whitespace-nowrap");
                                    span1_element.innerHTML = (value == 0) ? "Disponible  " : "Occupé";
                                    color = (value == 0) ? "border-green-500" : "border-red-500";
                                    li_element.classList.add("hour","flex","justify-between","items-center","border-2","rounded-lg","px-2","py-1","my-1","cursor-pointer","dark:bg-gray-600","dark:text-white",color);
                                    hour_formatted = ((parseInt(key)+8) < 10) ? "0"+(parseInt(key)+8) : (parseInt(key)+8);
                                    button_element.hour_formatted = hour_formatted
                                    li_element.innerHTML = hour_formatted+"h00";
                                    button_element.appendChild(span1_element);
                                    li_element.appendChild(button_element);
                                    hours_element.appendChild(li_element);
                                    button_element.addEventListener("click", function() 
                                    {
                                        alert(day_formatted + " " + this.hour_formatted + "h00" +" "+ "c'est le bon endroit")
                                    });
                                })
                            }
                            modal.show()
                        }
                    }
                });
            }
            if (day.toDateString() === new Date().toDateString())
            {
                day_element.classList.add("today","px-10","w-14","flex","justify-center","items-center","border-2","rounded-md","hover:border-emerald-500","hover:text-sky-900","cursor-pointer");
            }
            day_element.classList.add("select-none");
            week_element.appendChild(day_element);
        });
        weeks_element.appendChild(week_element);
    });
}

previous_month_element.addEventListener("click", function() 
{
    month_year.setMonth(month_year.getMonth() - 1);
    generateCalendar(month_year.getFullYear(), month_year.getMonth());
});

next_month_element.addEventListener("click", function()
{
    month_year.setMonth(month_year.getMonth() + 1);
    generateCalendar(month_year.getFullYear(), month_year.getMonth());
});

year = new Date().getFullYear();
month = new Date().getMonth();
generateCalendar(year, month);