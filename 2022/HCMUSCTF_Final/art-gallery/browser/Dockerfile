FROM node:17

RUN touch /etc/apt/apt.conf.d/99fixbadproxy \
    && echo "Acquire::http::Pipeline-Depth 0;" >> /etc/apt/apt.conf.d/99fixbadproxy \
    && echo "Acquire::http::No-Cache true;" >> /etc/apt/apt.conf.d/99fixbadproxy \
    && echo "Acquire::BrokenProxy true;" >> /etc/apt/apt.conf.d/99fixbadproxy \
    && apt-get update -o Acquire::CompressionTypes::Order::=gz \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* \
    && apt-get update -y

RUN apt-get update \
    && apt-get install -y wget gnupg dumb-init \
    && wget -q -O - https://dl-ssl.google.com/linux/linux_signing_key.pub | apt-key add - \
    && sh -c 'echo "deb [arch=amd64] http://dl.google.com/linux/chrome/deb/ stable main" >> /etc/apt/sources.list.d/google.list' \
    && apt-get update \
    && apt-get install -y google-chrome-stable fonts-ipafont-gothic fonts-wqy-zenhei fonts-thai-tlwg fonts-kacst fonts-freefont-ttf libxss1 \
      --no-install-recommends \
    && rm -rf /var/lib/apt/lists/*

RUN addgroup --system user && \
	adduser --system --ingroup user user && \
	mkdir /code && \
	chown user:user /code

USER user

WORKDIR /code
COPY . .
ENV PUPPETEER_SKIP_CHROMIUM_DOWNLOAD=true \
    PUPPETEER_EXECUTABLE_PATH=/usr/bin/google-chrome
RUN npm i

CMD ["dumb-init", "--", "node", "web.js"]