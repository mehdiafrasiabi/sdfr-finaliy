# رودمپ یادگیری ساخت AI Agent — از پایتون تا سیستم واقعی

فرض: پایه‌ی برنامه‌نویسی (Laravel/PHP/JS) از قبل داری، پس بخش «برنامه‌نویسی یاد بگیر» حذفه — فقط سراغ چیزهای مخصوص این حوزه می‌ریم. جلوی هر مرحله یه بازه‌ی زمانی واقع‌بینانه (با فرض ۱۰-۱۵ ساعت در هفته) و دوره‌ی پیشنهادی گذاشتم. هرجا دوره‌ی مشخصی نبود، نوشتم «دوره‌ای نیست، از مستندات/عمل یاد بگیر» — طبق خودت گفتی مشکلی نیست.

---

## مرحله ۰ — پایتون (۲ تا ۳ هفته)

چون از قبل برنامه‌نویس هستی، نیازی به دوره‌ی خیلی مقدماتی و کند نداری.

- **[CS50's Introduction to Programming with Python (Harvard, رایگان)](https://freecodingcourses.com/guides/harvard-cs50-free-course-guide-2026)** — پروژه‌محور و سریع، برای کسی که قبلاً کد زده مناسب‌تر از دوره‌های خیلی مقدماتیه.
- گزینه‌ی ملایم‌تر (اگه CS50P خیلی فشرده بود): **[Python for Everybody (Dr. Chuck, Coursera, رایگان برای audit)](https://www.dataquest.io/blog/best-python-courses/)**

چیزهایی که واقعاً لازمت می‌شه و باید رو‌شون تمرکز کنی: syntax پایه، کلاس‌ها، list/dict comprehension، مدیریت محیط مجازی (venv یا uv)، pip، و مهم‌تر از همه **asyncio** (چون فراخوانی API مدل‌ها معمولاً async انجام می‌شه). سراغ NumPy/Pandas/بخش‌های Data Science نرو — برای ساخت Agent لازم نیست.

---

## مرحله ۱ — کار مستقیم با API مدل (۱ تا ۲ هفته)

- **[Anthropic API Fundamentals (رسمی، رایگان، GitHub)](https://github.com/anthropics/courses)** — گرفتن کلید API، پارامترهای مدل، پرامپت چندرسانه‌ای، streaming.
- مکمل: OpenAI Cookbook (مستندات رسمی OpenAI، رایگان) — چون احتمالاً از طریق دروازه‌ای مثل AvalAI به مدل‌های OpenAI هم دسترسی داری.

---

## مرحله ۲ — پرامپت‌نویسی عملی (۲ هفته، می‌تونه موازی با بالا باشه)

- **[Prompt Engineering Interactive Tutorial + Real World Prompting (Anthropic، رسمی، رایگان)](https://github.com/anthropics/courses)**
- **ChatGPT Prompt Engineering for Developers (DeepLearning.AI، رایگان)**

---

## مرحله ۳ — خروجی ساخت‌یافته و Tool-calling (۲ تا ۳ هفته) ⭐ مهم‌ترین بخش برای پروژه‌ی خودتون

اینجا همون چیزیه که موتور تخصیص/برنامه‌ریز بهش نیاز داره — یاد می‌گیری چطور مدل رو مجبور کنی JSON با schema مشخص بده و چطور «ابزار» تعریف کنی که خودش صداش بزنه.

- **[Tool Use (Anthropic، رسمی، رایگان)](https://github.com/anthropics/courses)**
- **Functions, Tools and Agents with LangChain (DeepLearning.AI، رایگان)**

---

## مرحله ۴ — ارزیابی/Eval (۱ هفته)

- **[Prompt Evaluations (Anthropic، رسمی، رایگان)](https://github.com/anthropics/courses)** — یاد می‌گیری چطور یه مجموعه‌ی تست برای پرامپت‌هات بسازی، دقیقاً همون چیزی که برای «کِی بفهمم پرامپتم خراب شده» لازم داری.

---

## مرحله ۵ — فریمورک و Orchestration (۳ تا ۴ هفته)

- **Agentic AI (DeepLearning.AI، رایگان)** — چهار الگوی پایه (Reflection، Tool Use، Planning، Multi-Agent Collaboration) که مستقل از فریمورکن؛ قبل از انتخاب یه فریمورک خاص این رو بگذرون.
- **LangChain Academy (رایگان)** — برای یادگیری عمیق‌تر LangGraph، وقتی به سیستم چندAgent‌ی پیچیده‌تر (همون پنج‌نقشی که باهم طراحی کردیم) رسیدی.
- اختیاری/پولی و ارزون (~۱۵-۲۵ دلار): **Complete Agent & MCP Course (Udemy)** — پنج فریمورک رو با هم مقایسه می‌کنه (OpenAI Agents SDK، CrewAI، LangGraph، AutoGen، MCP)، ۱۷ ساعت، پروژه‌محور.

---

## مرحله ۶ — RAG (۱ هفته، اولویت پایین برای پروژه‌ی شما)

داده‌ی شما ساخت‌یافته‌ست (دیتابیس رابطه‌ای)، نه اسناد، پس RAG واقعی زیاد لازمت نمی‌شه؛ ولی خوبه مفهومش رو بلد باشی.

- **Building RAG Agents with LLMs (NVIDIA × DeepLearning.AI، رایگان)**

---

## مرحله ۷ — سمت Next.js / TypeScript (۱ تا ۲ هفته، وقتی به همیار عاطفی رسیدی)

- **Build a Support Agent with Vercel AI SDK (Scrimba، اشتراک Pro)** — چون همیار عاطفی رو با Next.js می‌سازی، این‌جا دقیقاً سمت TS ماجرا رو یاد می‌گیری.

---

## مرحله ۸ — پروژه‌ی واقعی خودت (۴ تا ۶+ هفته، بدون دوره)

دوره‌ای براش نیست و نباید هم باشه — همون Job تحلیل آزمون که قبلاً گفتم رو روی داده‌ی واقعی چند دانش‌آموز خودتون بساز. بیشترین یادگیری همین‌جا اتفاق می‌افته، نه تو دوره‌ها.

---

## جمع‌بندی زمان

| مرحله | زمان |
|---|---|
| پایتون | ۲-۳ هفته |
| API خام + پرامپت‌نویسی | ۳-۴ هفته (می‌تونن موازی باشن) |
| Tool-calling + Eval | ۳-۴ هفته |
| فریمورک/Orchestration | ۳-۴ هفته |
| RAG | ۱ هفته |
| Next.js/TS | ۱-۲ هفته |
| پروژه‌ی واقعی | ۴-۶+ هفته |
| **جمع کل** | **~۳ تا ۴ ماه** (پاره‌وقت، ۱۰-۱۵ ساعت در هفته) |

---

### منابع

- [Best Free Python Courses 2026 — freecodingcourses.com](https://freecodingcourses.com/guides/best-free-python-courses-2026)
- [Harvard CS50 Free Courses Guide 2026](https://freecodingcourses.com/guides/harvard-cs50-free-course-guide-2026)
- [anthropics/courses — رسمی، GitHub](https://github.com/anthropics/courses)
- [DeepLearning.AI Courses Guide — careery.pro](https://careery.pro/blog/ai-careers/deeplearning-ai-courses-guide)
- [Best AI Agent Courses 2026 — Scrimba](https://scrimba.com/articles/best-courses-to-learn-ai-agents-and-agentic-ai-in-2026/)
