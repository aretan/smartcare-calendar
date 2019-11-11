let dataSource = [
    {
        startDate: new Date(2019, 1, 4),
        endDate: new Date(2019, 1, 15),
        color: "yellow"
    }, {
        startDate: new Date(2019, 3, 5),
        endDate: new Date(2019, 5, 15),
        color: "yellow"
    }
];

// Create the calendar with the dataSource

let calendar = new Calendar('.calendar', {
    style: 'background',
    dataSource: dataSource,
    enableRangeSelection: true
});

//毎回画面更新をしていて若干チラつく

document.querySelector('.calendar').addEventListener('selectRange', function(e) {
    // Append the new data to dataSource 
    dataSource.push({
        startDate: e.startDate,
        endDate: e.endDate,
        color: "aqua"
    });

    // Set the updated dataSource as main source
    calendar.setDataSource(dataSource);
});