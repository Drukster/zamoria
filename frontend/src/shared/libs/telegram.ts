import WebApp from '@twa-dev/sdk';

export const initTelegramWebApp = () => {
    try {
        WebApp.ready();
        WebApp.expand();
        WebApp.enableClosingConfirmation();
        return WebApp;
    } catch (e) {
        console.error('Telegram WebApp init error:', e);
        return null;
    }
};

export const useTelegram = () => {
    const webApp = initTelegramWebApp();

    return {
        webApp,
        user: webApp?.initDataUnsafe?.user,
        themeParams: webApp?.themeParams,
        isTelegram: !!webApp
    };
};