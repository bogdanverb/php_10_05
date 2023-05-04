Holidays = () => {
    // create a new element ib body
    let body = document.getElementsByTagName('body')[0];
    let block = document.createElement('div')
    block.id = 'live_span_holiday';
    body.appendChild(block);

    let CorrectMonth = (diff) =>{
        switch(true){
            case (diff === 1):
                return " залишився один місяць";
            case (diff < 5):
                return " залишилось " + diff + " місяці";
            case (diff > 4):
                return " залишилось " + diff + " місяців";
        }
    }
    let CorrectDay = (diff) =>{
        switch(true){
            case (diff === 1):
                return " залишився один день";
            case (diff < 5):
                return " залишилось " + diff + " дні";
            case (diff > 4):
                return " залишилось " + diff + " днів";
        }
    }
    let msg = "";
    let month = new Date().getMonth();
    let day = new Date().getDate();
    let Holidays = {
        'Нового Року' :  [1,0,"Новий Рік"],
        'Різдва Христового' : [7,0,"Різдво Христове"],
        'Дня Соборності України' : [22,0,"День Соборності України"],
        'Початку весняного семестру' : [30,0,"Початок весняного семестру"],
        'День Святого Валентина' : [14,1,"День Святого Валентина"],
        'Міжнародного Жіночого Дня' :  [8,2,"Міжнародний Жіночий День"],
        'Великдня' :  [16,3,"Великдень"],
        'Дня Праці' :  [1,4,"День Праці"],
        'Дня перемоги над нацизмом у Другій світовій війні' :  [9,4,"День перемоги над нацизмом у Другій світовій війні"],
        'Трійці' :  [4,5,"Трійця"],
        'Дня Конституції України' :  [28,5,"День Конституції України"],
        'Дня Державного Прапора України' :  [23,7,"День Державного Прапора України"],
        'Дня Незалежності України' :  [24,7,"День Незалежності України"],
        'Дня Захисників України' :  [14,9,"День Захисників України"],
        'Дня Збройних Сил України' :  [6,11,"День Збройних Сил України"],
        'Католицького Різдва' :  [25,11,"Католицьке Різдво"]
    };
    for(let prop in Holidays){
        let HolidaysDate = Holidays[prop]; // [1] - month .. [0] - day .. [2] - Альтернативна назва
        let CorrectM = CorrectMonth(HolidaysDate[1] - month);
        let CorrectD = CorrectDay(HolidaysDate[0] - day);
        if(HolidaysDate[1] === month){
            if(HolidaysDate[0] === day){
                msg = 'Сьогодні '+ HolidaysDate[2];
                console.log('Сьогодні '+ HolidaysDate[2]);
                break;
            } 
        } 
        if(HolidaysDate[1] <= month && HolidaysDate[0] <= day){
            msg = "Чекаємо Нового Року";
        }
        if(HolidaysDate[1] > month){
            msg = "До " + prop  + CorrectM;
            break;
        }
        if(HolidaysDate[1] === month){
            if(HolidaysDate[0] >= day){
                msg = "До " + prop + CorrectD ;
                break;
            }
        }
    }
    block.innerHTML += `Найближча подія:<br>${msg}`;
}

Holidays();

