import Modal from '../../../assets/js/components/modal.vue';
import Vue from 'vue';

describe('Modal.vue', function() {
    test('should have props', function() {
        expect(Modal.props.isActive).not.toBeUndefined();
        expect(Modal.props.message).not.toBeUndefined();
        expect(Modal.props.cta).not.toBeUndefined();
    });

    test('Defaults to be set', function() {
        const vm = new Vue(Modal).$mount()
        expect(vm.cta).toEqual('Close');
        expect(vm.message).toEqual('');
        expect(vm.isActive).toEqual(false);
    });
});