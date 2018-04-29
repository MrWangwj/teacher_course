## 教师课表管理系统
#### 1. 克隆项目
```
git clone git@github.com:MrWangwj/teacher_course.git
```
#### 2. 生成 `.env` 文件
```
cp .env.example .env
```
#### 3. 安装 `composer` 依赖
```
composer install
```

#### 4. 创建数据库，并执行数据迁移
```
php artisan migrate
```
#### 5. 插入需要的数据
```
php artisan db:seed
```

#### 6. 安装 `npm` 依赖
```
npm install
```

#### 7. 编译
```
npm run dev
```
#### 8. 配置虚拟主机

