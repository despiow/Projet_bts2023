month_year_element = document.getElementById("month_year");
previous_month_element = document.getElementById("previous_month");
next_month_element = document.getElementById("next_month");
weeks_element = document.getElementById("weeks");
month_year = new Date();
dayNames_element = document.getElementById("dayNames");
dayNames = ["Lundi", "Mardi", "Mercredi", "Jeudi", "Vendredi"];
dayNames.forEach(function(dayName) {
    var dayName_element = document.createElement("span");
    dayName_element.classList.add("dayName","select-none","px-10","border","rounded-sm","w-14","h-5","flex","items-center","justify-center","border-green-500","text-green-500","shadow-md");
    dayName_element.innerHTML = dayName;
    dayNames_element.appendChild(dayName_element);
});
function getWeeks(year, month) {
    month_year = new Date(year, month);
    month_year_element.innerHTML = month_year.toLocaleString("fr-FR", { month: "long" }) + " " + month_year.getFullYear();
    month_year_element.classList.add("text-2xl","font-bold","text-center","text-green-500","pb-2","select-none");
    var weeks = [];
    var firstDate = new Date(year, month, 1);
    var lastDate = new Date(year, month + 1, 0);
    var numDays = lastDate.getDate();
    // skip days before the first Monday
    var startDay = firstDate.getDay();
    if (startDay === 0) startDay = 7;
    if (startDay > 1) {
        firstDate.setDate(firstDate.getDate() - (startDay - 1));
    }
    // iterate the days of the month
    var curDate = firstDate;
    while (curDate <= lastDate) {
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

function generateCalendar(year, month) {
    weeks_element.innerHTML = "";
    var weeks = getWeeks(year, month);
    weeks.forEach(function(week) {
        var week_element = document.createElement("div");
        week_element.classList.add("week","flex","justify-between","font-medium","text-sm","pb-2");
        week.forEach(function(day) {
            var day_element = document.createElement("span");
            day_element.classList.add("day","px-10","w-14","flex","justify-center","items-center");
            day_element.innerHTML = day.getDate();
            if (day.getMonth() !== month) {
                day_element.classList.add("different_month","px-10","w-14","flex","justify-center","items-center","text-gray-400");
            }else{
                day_element.classList.add("border","hover:border-green-500","hover:text-green-500","cursor-pointer");
                day_element.addEventListener("click", function() {
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
                    day = dd + '/' + mm + '/' + yyyy;
                    alert("Date cliquée : " + day);
                });
            }
            if (day.toDateString() === new Date().toDateString()) {
                day_element.classList.add("today","px-10","w-14","flex","justify-center","items-center","border","hover:border-blue-500","hover:text-blue-500","cursor-pointer");
            }
            day_element.classList.add("select-none");
            week_element.appendChild(day_element);
        });
        weeks_element.appendChild(week_element);
    });
}
previous_month_element.addEventListener("click", function() {
    month_year.setMonth(month_year.getMonth() - 1);
    generateCalendar(month_year.getFullYear(), month_year.getMonth());
});
next_month_element.addEventListener("click", function() {
    month_year.setMonth(month_year.getMonth() + 1);
    generateCalendar(month_year.getFullYear(), month_year.getMonth());
});
year = new Date().getFullYear();
month = new Date().getMonth();
generateCalendar(year, month);