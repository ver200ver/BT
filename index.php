<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta content="IE=edge" http-equiv="X-UA-Compatible">
    <meta content="width=device-width, initial-scale=1" name="viewport">
    <title></title>
    <!--[if lt IE 9]>
<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<![endif]-->
    <link href="css/ui.min.css" rel="stylesheet" type="text/css">
    <link href="css/font.min.css" rel="stylesheet" type="text/css">
    <script type="text/javascript" src="js/regression.js"></script>
    <script src="js/jquery.min.js"></script>
    <style>
        html,
        body,
        #container {
            width: 100%;
            height: 100%;
            margin: 0;
            padding: 0;
        }
        
        .down,
        .up {
            width: 63px;
            height: 63px;
        }
    </style>
</head>

<body> <img src="img/kainos-logo-sticky.png"></img>
    <center>
        <h1>CoinTrends - Challenge 2018 </h1> </center>
    <div id="container"></div>
    <center>
        <div class="img"></div>
    </center>
    <script src="js/base.min.js"></script>
    <script src="js/ui.min.js"></script>
    <script src="js/exports.min.js"></script>
    <script type="text/javascript">
        var chart = docs.line();
        // turn on chart animation
        chart.animation(true);
        // set chart padding
        chart.padding([10, 20, 5, 20]);
        // turn on the crosshair
        chart.crosshair().enabled(true).yLabel(false).yStroke(null);
        // set tooltip mode to point
        chart.tooltip().positionMode('point');
        // set chart title text settings
        chart.title('Chart Title');
        var logScale = docs.scales.log();
        logScale.minimum(1);
        // set scale for the chart, this scale will be used in all scale dependent entries such axes, grids, etc
        chart.yScale(logScale);
        // set yAxis title
        chart.yAxis().title('');
        chart.xAxis().labels().padding(5);

        function paint(input, name) {
            dataSet = docs.data.set(input);
            seriesData_1 = dataSet.mapAs({
                'x': 0,
                'value': 1
            });
            series_1 = chart.line(seriesData_1);
            series_1.name(name);
            series_1.hovered().markers().enabled(true).type('circle').size(4);
            series_1.tooltip().position('right').anchor('left-center').offsetX(5).offsetY(5);
            // turn the legend on
            chart.legend().enabled(true).fontSize(13).padding([0, 0, 10, 0]);
            // set container id for the chart
            chart.container('container');
            // initiate chart drawing
            chart.draw();
        }
        var flag = 0;
        var BTCUSD;
        var ETHUSD;
        var LTCUSD;
        var monthNames = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];

        function get(url, subIndex) {
            $.get(url, function(returnedData) {
                onAjaxSuccess(returnedData, subIndex)
            });
        }

        function addImg(type, text) {
            if (type == 'up') type = "arrowupgreen"
            else type = "arrowdownred"
            $(".img").append("<b style='padding-right: 74px;'><img class='up' src='img/" + type + ".svg'><text>" + text + "</text></b>")
        }

        function onAjaxSuccess(data, name) {
            if (name == 'BTCUSD') {
                BTCUSD = data;
                flag++;
            } else if (name == 'ETHUSD') {
                ETHUSD = data;
                flag++;
            } else if (name == 'LTCUSD') {
                LTCUSD = data;
                flag++;
            } else console.log("Eror name=" + name);
            if (flag == 3) {
                paint(getBT('BTCUSD', 2017, 1), 'BTCUSD')
                trends = trend(getBT('BTCUSD', 2017, 0), "less0")
                if (trends[trends.length - 1][1] > trends[trends.length - 2][1]) addImg("up", "BTCUSD Growth")
                else addImg("up", "BTCUSD Drop")
                paint(trends, "BTCUSD Trend")
                paint(getBT('ETHUSD', 2017, 1), 'ETHUSD')
                trends = trend(getBT('ETHUSD', 2017, 0), "less0")
                if (trends[trends.length - 1][1] > trends[trends.length - 2][1]) addImg("up", "BTCUSD Growth")
                else addImg("up", "BTCUSD Drop")
                paint(trends, "ETHUSD Trend")
                paint(getBT('LTCUSD', 2017, 1), 'LTCUSD')
                trends = trend(getBT('LTCUSD', 2017, 0), "less0")
                if (trends[trends.length - 1][1] > trends[trends.length - 2][1]) addImg("up", "BTCUSD Growth")
                else addImg("up", "BTCUSD Drop")
                paint(trends, "LTCUSD Trend")
            }
        }
        get("https://apiv2.bitcoinaverage.com/indices/global/history/BTCUSD?period=alltime&format=json", 'BTCUSD');
        get("https://apiv2.bitcoinaverage.com/indices/global/history/ETHUSD?period=alltime&format=json", 'ETHUSD');
        get("https://apiv2.bitcoinaverage.com/indices/global/history/LTCUSD?period=alltime&format=json", 'LTCUSD');

        function addToArray(array, value, size, ) {
            array[array.length] = new Array(size);
            array[array.length - 1] = value;
            //return array;
        }

        function getXX(data, index) {
            value = data[index][1]
            count = 1
            xx = data[index][0]
            res = new Array(0)
            for (i = index; i < data.length; i++) {
                if (xx == data[i][0]) {
                    tmp = new Array(2)
                    tmp[0] = data[i][0]
                    tmp[1] = data[i][1]
                    addToArray(res, tmp, 2)
                } else {
                    return {
                        'data': res,
                        'index': i
                    };
                }
            }
            return {
                'data': res,
                'index': -1
            };
        }

        function getXXcount(data) {
            reDataGet = {
                index: 0
            };
            countData = 0
            while (true) {
                reDataGet = getXX(data, reDataGet['index']);
                if (reDataGet['index'] == -1) return countData;
                countData = countData + 1;
            }
            return countData;
        }

        function trend(data, type) {
            allRes = new Array(0);
            resss = new Array(0);
            reData = {
                index: 0
            }
            len = getXXcount(data)
            ik = 0
            for (i = 0; i < len; i = i++) {
                resss = new Array(0);
                reData['index'] = i;
                for (j = 0; j <= 3; j++) {
                    ik++
                    reData = getXX(data, reData['index']);
                    addToArray(resss, reData['data'][0], 2)
                    if (reData['index'] == -1) break;
                }
                arrfff = fitData(resss).data
                for (k = 0; k < arrfff.length; k++) {
                    addToArray(allRes, arrfff[k], 2)
                }
            }
            for (i = 0; i < allRes.length; i++) {
                allRes[i][0] = monthNames[allRes[i][0]]
            }
            return allRes;
        }
        function getBT(type, year, flagShort) {
            BT = '';
            if (type == 'BTCUSD') BT = BTCUSD;
            else if (type == 'ETHUSD') BT = ETHUSD;
            else if (type == 'LTCUSD') BT = LTCUSD;
            length = BT.length;
            lengthSubArray = 2;
            res = new Array(0);
            index = 0;
            for (i = 0; i < length; i++) {
                tmp = new Date(BT[i].time)
                if (tmp.getFullYear() != year) continue;
                res.length = res.length + 1;
                res[res.length - 1] = new Array(lengthSubArray);
                if (flagShort == 1) {
                    res[index][0] = monthNames[tmp.getMonth()]
                } else {
                    res[index][0] = tmp.getMonth()
                }
                res[index][1] = BT[i].average;
                index = index + 1;
            }
            tmpRes = res
            length = res.length
            res = new Array(length)
            for (i = 0; i < length; i++) {
                res[length - i - 1] = tmpRes[i];
            }
            ress = new Array(0)
            value = res[0][1]
            count = 1;
            xx = res[0][0]
            for (i = 0; i < res.length; i++) {
                if (xx == res[i][0]) {
                    value = value + res[i][1];
                    count++;
                } else {
                    tmp = new Array(2)
                    tmp[0] = xx;
                    tmp[1] = value / count;
                    addToArray(ress, tmp, 2)
                    xx = res[i][0]
                    value = res[i][1]
                    count = 1
                }
            }
            return ress;
        }
    </script>
</body>

</html>