function getCookie(name) {
  const match = document.cookie.match(new RegExp("(^| )" + name + "=([^;]+)"));
  if (match) return match[2];
  return null;
}

async function getCSRFToken() {
  try {
    const response = await fetch("http://127.0.0.1:8000/sanctum/csrf-cookie", {
      credentials: "include",
    });

    if (!response.ok) throw new Error("Failed to get CSRF token");

    const token = getCookie("XSRF-TOKEN");
    return decodeURIComponent(token);
  } catch (error) {
    console.error("Error getting CSRF token:", error);
    return null;
  }
}

async function makeSessionRequest(url, options = {}) {
  const csrfToken = await getCSRFToken();

  const defaultOptions = {
    credentials: "include",
    headers: {
      "Content-Type": "application/json",
      Accept: "application/json",
      ...(csrfToken && { "X-XSRF-TOKEN": csrfToken }),
    },
  };

  const finalOptions = {
    ...defaultOptions,
    ...options,
    headers: {
      ...defaultOptions.headers,
      ...(options.headers || {}),
    },
  };

  return fetch(url, finalOptions);
}

async function isLoggedIn() {
  try {
    const response = await makeSessionRequest("http://127.0.0.1:8000/api/user");
    return response.ok;
  } catch (error) {
    console.error("Error checking login status:", error);
    return false;
  }
}