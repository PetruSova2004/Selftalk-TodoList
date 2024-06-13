# Dockerfile для frontend
FROM node:18

# Копирование исходного кода
COPY ./ /app

# Установка зависимостей и сборка приложения
WORKDIR /app
RUN npm install
RUN npm run build

# Запуск сервера
CMD ["npm", "start"]
