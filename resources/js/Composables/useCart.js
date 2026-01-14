import { router } from '@inertiajs/vue3';
import { route } from 'ziggy-js';
import { Ziggy } from '../ziggy';
import { ref } from 'vue';

export default function useCart() {
  const loading = ref(false);

  const submit = (method, name, params = {}, payload = {}, options = {}) => {
    loading.value = true;

    const url = route(name, params, false, Ziggy);

    router[method](url, payload, {
      preserveScroll: true,
      onFinish: () => {
        loading.value = false;
      },
      ...options,
    });
  };

  const addToCart = (productId, quantity = 1, options = {}) => {
    submit('post', 'cart.add', productId, { quantity }, options);
  };

  const updateItem = (productId, quantity, options = {}) => {
    submit('post', 'cart.update', productId, { quantity }, options);
  };

  const removeItem = (productId, options = {}) => {
    submit('post', 'cart.remove', productId, {}, options);
  };

  const clearCart = (options = {}) => {
    submit('post', 'cart.clear', {}, {}, options);
  };

  const gotoCheckout = () => {
    router.visit(route('checkout', undefined, false, Ziggy), {
      preserveState: false,
    });
  };

  return {
    loading,
    addToCart,
    updateItem,
    removeItem,
    clearCart,
    gotoCheckout,
  };
}
