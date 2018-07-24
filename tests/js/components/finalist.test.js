import FinalistVue from '../../../assets/js/components/finalist.vue';
import {Finalist} from '../../../assets/js/model/finalist.js';
import { mount } from 'vue-test-utils'
import {AsyncResponse} from '../../../assets/js/view-helper/finalist-edit.js';

describe('Finalist.vue', function() {

    describe('When user creates a new finalist', function() {
        const vue = mount(FinalistVue, {
            propsData: {
                asyncResponse: new AsyncResponse
            }
        });

        it('should have props', function() {
            expect(FinalistVue.props.finalist).not.toBeUndefined();
        });

        it('should have initial data', function() {
            expect(vue.vm.iFinalist).toEqual(new Finalist);
        });

        it('should not allow user to save before data is entered', function() {
            expect(vue.vm.canSave).toBe(false);
        });
    });
});