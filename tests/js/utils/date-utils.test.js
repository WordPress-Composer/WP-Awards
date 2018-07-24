import {hours, minutes, months, days, getYears} from '../../../assets/js/utils/date-utils.js';
import Vue from 'vue';

describe('Date Utils', function() {

    test('hours()', function() {
        let time = hours();
        expect(time[0]).toEqual('00');
        expect(time[1]).toEqual('01');
        expect(time[2]).toEqual('02');
        expect(time[3]).toEqual('03');
        expect(time[4]).toEqual('04');
        expect(time[5]).toEqual('05');
        expect(time[6]).toEqual('06');
        expect(time[7]).toEqual('07');
        expect(time[8]).toEqual('08');
        expect(time[9]).toEqual('09');
        expect(time[10]).toEqual('10');
        expect(time[11]).toEqual('11');
        expect(time[12]).toEqual('12');
        expect(time[13]).toEqual('13');
        expect(time[14]).toEqual('14');
        expect(time[15]).toEqual('15');
        expect(time[16]).toEqual('16');
        expect(time[17]).toEqual('17');
        expect(time[18]).toEqual('18');
        expect(time[19]).toEqual('19');
        expect(time[20]).toEqual('20');
        expect(time[21]).toEqual('21');
        expect(time[22]).toEqual('22');
        expect(time[23]).toEqual('23');
    });

    test('minutes()', function() {
        let time = minutes();
        expect(time[0]).toEqual('00');
        expect(time[1]).toEqual('15');
        expect(time[2]).toEqual('30');
        expect(time[3]).toEqual('45');
    });

    test('months()', function() {
        let expected = [
            { number: '01', name: 'January'},
            { number: '02', name: 'February'},
            { number: '03', name: 'March'},
            { number: '04', name: 'April'},
            { number: '05', name: 'May'},
            { number: '06', name: 'June'},
            { number: '07', name: 'July'},
            { number: '08', name: 'August'},
            { number: '09', name: 'September'},
            { number: '10', name: 'October'},
            { number: '11', name: 'November'},
            { number: '12', name: 'December'}
        ];
        expect(months()).toEqual(expected);
    });
    
    test('days()', function() {
        let expected = [
            '01',
            '02',
            '03',
            '04',
            '05',
            '06',
            '07',
            '08',
            '09',
            '10',
            '11',
            '12',
            '13',
            '14',
            '15',
            '16',
            '17',
            '18',
            '19',
            '20',
            '21',
            '22',
            '23',
            '24',
            '25',
            '26',
            '27',
            '28',
            '29',
            '30',
            '31'
        ];
        expect(days()).toEqual(expected);
    });

    describe('getYears()', function() {
        test('Default getYears()', function() {
            let years = getYears();
            expect(years[0]).toEqual((new Date).getFullYear() + 3);
            expect(years[1]).toEqual((new Date).getFullYear() + 2);
            expect(years[years.length - 2]).toEqual(2016);
            expect(years[years.length - 1]).toEqual(2015);
        })
    });

    describe('getYears(2010)', function() {
        test('Default getYears()', function() {
            let years = getYears(2010);
            expect(years[years.length - 1]).toEqual(2010);
        });
    })

    describe('getYears(1999, 10) Starting from 2011 and 10 years ahead', function() {
        test('Default getYears()', function() {
            let years = getYears(1999, 10);
            expect(years[0]).toEqual((new Date).getFullYear() + 10);
            expect(years[years.length - 1]).toEqual(1999);
        });
    })
});