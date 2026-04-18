
    // 1) Обработка кликов навигации (имитация роутинга, учебный пример)
    const navLinks = ['nav-home', 'nav-catalog', 'nav-contacts', 'nav-about'];
    navLinks.forEach(id => {
        const element = document.getElementById(id);
        if (element) {
            element.addEventListener('click', (e) => {
                e.preventDefault();
                const text = element.innerText;
                showToastMessage(`🔍 Навигация: "${text}" — здесь будет роутинг / переход на PHP-страницы`);
                // Для учебного примера просто скроллим к верху или ничего не ломаем
                window.scrollTo({ top: 0, behavior: 'smooth' });
            });
        }
    });

    // 2) Обработка формы: валидация + имитация отправки (демонстрация данных)
    const form = document.getElementById('demoForm');
    const formResultDiv = document.getElementById('formResult');

    function showToastMessage(msg, isError = false) {
        const toast = document.getElementById('liveToast');
        toast.style.opacity = '1';
        toast.style.backgroundColor = isError ? '#b91c1c' : '#1e293b';
        toast.textContent = msg;
        setTimeout(() => {
            toast.style.opacity = '0';
            setTimeout(() => {
                if(!isError) toast.style.backgroundColor = '#1e293b';
            }, 300);
        }, 2800);
    }

    form.addEventListener('submit', (event) => {
        event.preventDefault(); // предотвращаем реальную отправку (для будущего PHP убрать в боевой версии)
        
        // получаем значения
        const nameInput = document.getElementById('userName');
        const emailInput = document.getElementById('userEmail');
        const messageInput = document.getElementById('message');
        
        const name = nameInput.value.trim();
        const email = emailInput.value.trim();
        const msgText = messageInput.value.trim();
        
        // простая клиентская валидация
        if (name === '') {
            showToastMessage('❌ Пожалуйста, укажите имя', true);
            nameInput.style.borderColor = '#dc2626';
            return;
        } else {
            nameInput.style.borderColor = '#cbd5e1';
        }
        
        if (email === '' || !email.includes('@') || !email.includes('.')) {
            showToastMessage('❌ Введите корректный email (например, name@domain.com)', true);
            emailInput.style.borderColor = '#dc2626';
            return;
        } else {
            emailInput.style.borderColor = '#cbd5e1';
        }
        
        // Имитация успешной отправки. Для будущего PHP здесь будет AJAX или стандартный POST.
        // Демонстрация собранных данных
        const previewMessage = `
             <strong>Данные готовы для отправки на сервер (PHP + MySQL):</strong><br>
             Имя: ${escapeHtml(name)}<br>
             Email: ${escapeHtml(email)}<br>
             Сообщение: ${escapeHtml(msgText) || '(пусто)'}<br>
            <span style="font-size:0.85rem;">🔄 При переходе на PHP, эти данные вставятся в таблицу БД.</span>
        `;
        formResultDiv.innerHTML = previewMessage;
        formResultDiv.style.background = '#e6f7e6';
        formResultDiv.style.padding = '12px 16px';
        formResultDiv.style.borderRadius = '1rem';
        formResultDiv.style.marginTop = '1rem';
        formResultDiv.style.borderLeft = '4px solid #10b981';
        
        // Показываем toast-уведомление
        showToastMessage(` Спасибо, ${name}! Данные сохранены в консоли (имитация)`);
        
        // Дополнительно выводим в консоль для преподавателя
        console.group(' Данные формы (будущий MySQL)');
        console.log('Имя:', name);
        console.log('Email:', email);
        console.log('Сообщение:', msgText);
        console.log('Время:', new Date().toLocaleString());
        console.groupEnd();
        
        // Очищать форму не будем, чтобы студент видел введённое. Но можно сбросить (по желанию)
        // Однако для демонстрации сбросим результат при повторной отправке? Оставляем.
        // Но чтобы не нагромождать, просто оставляем как есть.
        
        // Имитация: добавим стили плавно
        setTimeout(() => {
            // для наглядности через 4 секунды убираем подсказку? не обязательно
        }, 100);
    });
    
    // Вспомогательная функция для защиты от XSS при отображении в демо (простой escape)
    function escapeHtml(str) {
        if (!str) return '';
        return str.replace(/[&<>]/g, function(m) {
            if (m === '&') return '&amp;';
            if (m === '<') return '&lt;';
            if (m === '>') return '&gt;';
            return m;
        }).replace(/[\uD800-\uDBFF][\uDC00-\uDFFF]/g, function(c) {
            return c;
        });
    }
    
    // 3) Дополнительно: обработка кликов по карточкам - учебный пример (показать, что элементы интерактивны)
    const cards = document.querySelectorAll('.demo-card');
    cards.forEach((card, idx) => {
        card.addEventListener('click', () => {
            const title = card.querySelector('h3')?.innerText || 'карточка';
            showToastMessage(`🖱️ Клик по "${title}" — в будущем детальная страница из БД`);
        });
        card.style.cursor = 'pointer';
    });
    
    // 4) Простое улучшение: убираем стандартное выделение для кнопок, добавляем приятный UX
    const submitBtn = document.getElementById('submitBtn');
    if(submitBtn) {
        submitBtn.addEventListener('mousedown', (e) => e.preventDefault()); // небольшая хитрость для предотвращения blur, необязательно
    }
    
    // 5) Добавляем сообщение для студентов о готовности к MySQL
    console.log("%cСайт полностью готов к интеграции с PHP/MySQL. Все атрибуты name, структура форм и семантика соблюдены.", "color: #2563eb; font-size: 14px;");
    
    // Адаптивная проверка + доступность: проставляем aria-current (для активной навигации – по желанию)
    // Просто показываем идеологию
    const currentNav = document.querySelector('.main-nav a');
    if(currentNav) currentNav.setAttribute('aria-current', 'page');