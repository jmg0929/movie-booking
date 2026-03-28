# 🎬 실시간 영화 예매 시스템 (Legacy to Cloud Migration)

> **1학년 당시 개발했던 PHP 기반 영화 예매 시스템을 AWS 클라우드 환경으로 마이그레이션하고 보안을 강화하는 프로젝트입니다.**

---

## 📌 프로젝트 개요
- **기존 개발 기간:** 2024.04 ~ 2024.05 (대학교 1학년 과제)
- **현재 목표:** 온프레미스(Local) 환경의 레거시 코드를 AWS로 이관하며 인프라 및 보안 역량 강화
- **기술 스택 (Legacy):** `PHP`, `Apache`, `MySQL`, `JavaScript`, `HTML/CSS`

## 🏗️ 시스템 아키텍처 (Update 예정)
- [ ] **Step 1:** 로컬 환경의 코드를 GitHub에 형상 관리 (완료)
- [ ] **Step 2:** AWS EC2 및 RDS를 활용한 인프라 구축 및 데이터 마이그레이션
- [ ] **Step 3:** AWS WAF, SSL 적용을 통한 웹 보안 강화
- [ ] **Step 4:** S3를 활용한 정적 리소스 관리 최적화

## 🛠️ 주요 기능 (Legacy)
- 사용자의 실시간 영화 목록 조회 및 상세 정보 확인
- AJAX를 활용한 실시간 좌석 선택 및 예매 프로세스
- 관리자 페이지 (영화 등록, 스케줄 관리, 예매 현황 대시보드)

## 🔍 개선 포인트 (Roadmap)
1. **보안성 향상:** SQL Injection 방어를 위한 Prepared Statement 적용 및 AWS WAF 도입
2. **가용성 확보:** AWS RDS를 통한 데이터 백업 및 관리형 DB 서비스 활용
3. **인프라 현대화:** 서버리스(Lambda) 또는 컨테이너 기술 검토

---

## 👨‍💻 작성자
- **전무건 (Jun moogeon)**
- 연락처: jmg092909@naver.com
- 블로그: https://jmg092909.tistory.com
