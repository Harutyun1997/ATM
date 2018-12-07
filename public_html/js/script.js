function Atm(nominalsCount) {
    this.cells = {
        5000: 0,
        2000: 0,
        1000: 0,
        500: 0,
        200: 0,
        100: 0,
        50: 0
    };

    this.addNominals = function (nominalsCount) {
        if (typeof nominalsCount !== "object") {
            return false;
        }

        for (let nominal in this.cells) {
            if (this.cells.hasOwnProperty(nominal) && nominalsCount[nominal] > 0) {
                this.cells[nominal] += nominalsCount[nominal];
            }
        }
    };

    this.getSum = function () {
        let sum = 0;

        for (let nominalName in this.cells) {
            if (this.cells.hasOwnProperty(nominalName)) {
                sum += parseInt(nominalName) * this.cells[nominalName];
            }
        }

        return sum;
    };

    this.giveMoney = function (amount) {

        if (amount > this.getSum()) {
            throw 'Ошибка! Операция не удалось.';
        }

        let response = {};
        let arr = Object.keys(this.cells);
        let banknotesATM = [];

        arr.forEach(function (element) {
            banknotesATM.push(parseInt(element))
        });

        banknotesATM.sort(function (a, b) {
            return b - a;
        });

        let count = 0;
        response = amount;
        let object = {};
        banknotesATM.forEach(function (kup) {
            object[kup] = 0;
        });
        do {
            if (banknotesATM.length > count) {
                response = checkGiveMoney(response, banknotesATM, this.cells, object, count);
                count++;
            }
            else {
                throw 'Ошибка! Операция не удалось.';
            }

        } while (typeof response !== "object");

        return response;
    };

    this.addNominals(nominalsCount);
}

function checkGiveMoney(amount, banknotesATM, nominals, object, count) {


    for (let i = count; i < banknotesATM.length; i++) {

        while (amount >= banknotesATM[i] && nominals[banknotesATM[i]] - object[banknotesATM[i]] > 0) {
            amount -= banknotesATM[i];
            object[banknotesATM[i]]++;

            if (amount === 0) {
                for (i; i >= 0; i--) {
                    nominals[banknotesATM[i]] -= object[banknotesATM[i]];
                }

                return object;
            }
        }
    }

    banknotesATM.forEach(function (kup) {
        if (object[kup] > 0) {
            amount += kup;
            object[kup]--;
        }
    });

    return amount;
}


let atmInitialCounts = [
    {
        5000: 50,
        2000: 100,
        1000: 150,
        500: 250,
        200: 400,
        100: 800,
        50: 1000
    },
    {
        5000: 70,
        2000: 120,
        1000: 250,
        500: 350,
        200: 600,
        100: 900,
        50: 1200
    },
    {
        5000: 20,
        2000: 50,
        1000: 80,
        500: 130,
        200: 200,
        100: 400,
        50: 550
    }
];

let atms = [];

atmInitialCounts.forEach(function (atmCounts) {
    atms.push(new Atm(atmCounts));
});


function getAllAtmsSum() {
    let sum = 0;

    atms.forEach(function (atm) {
        sum += atm.getSum();
    });

    return sum;
}

function resetAtms() {
    let count = 0;
    let element = 0;
    let obj = {};

    for (let i = 0; i < atms.length; i++) {
        for (let nominal in atms[i].cells) {
            element = parseInt(nominal);
            if (atms[i].cells[element] < atmInitialCounts[i][element]) {
                count = atmInitialCounts[i][element] - atms[i].cells[element];
                obj[element] = count;
                atms[i].addNominals(obj);
            }
        }
    }
}

let arr = [1, 2, 3, 4, 5];



console.log(arr.length);


