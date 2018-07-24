import ChosenDate from '../../../assets/js/model/chosen-date.js';
import {expect} from 'chai';

/**
 * Tests chosen date model
 * @author Gemma Black <gblackuk@gmail.com>
 */
describe('Chosen Date Model', function() {

    test('Should have default start day of 01', function() {
        let date = new ChosenDate();
        expect(date.day).to.equal('01');
    });

    test('Should have default month of 01', function() {
        let date = new ChosenDate();
        expect(date.month).to.equal('01');
    });

    test('Should have default year of this year (whatever that may be)', function() {
        let date = new ChosenDate();
        expect(date.year).to.equal((new Date()).getFullYear());
    });

    test('Should have default hours of 00', function() {
        let date = new ChosenDate();
        expect(date.hours).to.equal('00');
    });

    test('Should have default minutes of 00', function() {
        let date = new ChosenDate();
        expect(date.minutes).to.equal('00');
    });

    test('Should hydrate with input options', function() {
        let date = new ChosenDate({
            year: '2017',
            hours: '10',
            minutes: '30',
            day: '01',
            month: '05'
        });
        expect(date.year).to.equal('2017');
        expect(date.month).to.equal('05');
        expect(date.day).to.equal('01');
        expect(date.minutes).to.equal('30');
        expect(date.hours).to.equal('10');
    });

    test('Should return all date options strings', function() {
        let date = new ChosenDate({
            year: 2017,
            hours: 10,
            minutes: 30,
            day: 1,
            month: 5
        });
        expect(date.year).to.be.a('string');
        expect(date.month).to.be.a('string');
        expect(date.day).to.be.a('string');
        expect(date.hours).to.be.a('string');
        expect(date.minutes).to.be.a('string');
    });
});