const CSRF_META_SELECTOR = 'meta[name="csrf-token"]'

/**
 * Lit le token CSRF exposé dans le layout principal.
 */
export const getCsrfToken = () => {
  if (typeof document === 'undefined') {
    return ''
  }

  return document.querySelector(CSRF_META_SELECTOR)?.getAttribute('content') ?? ''
}

/**
 * Garantit que le payload contient bien le token CSRF.
 */
export const injectCsrfToken = (payload) => {
  const token = getCsrfToken()

  if (!token) {
    return payload
  }

  if (payload instanceof FormData) {
    if (!payload.has('_token')) {
      payload.append('_token', token)
    }
    return payload
  }

  return {
    ...payload,
    _token: token
  }
}
