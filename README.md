


# Chi Streamer Event Viewer


This is a simple event based application for following the activities of a your favourite streamer.
[Demo Here](https://chistreamer.herokuapp.com) 

**

## Installation

For frontend:

    # cd frontend
    # npm install
    # npm run build

For backend:

    # cd backend
    # composer install

 


**

## Architecture 


The Application is a split into two logical sections (Frontend UI and Backend Webservices) each with it's own stack. This allows for greater modularity and scalability across infrastructure and teams hence the frontend is completely decoupled from the backend.

The current architecture ensures the application can scale horizontally and vertically considering both frontend and backend can be hosted separately utilizing maximum server resources and bandwidth when required. Frontend teams may come up with faster prototypes and features while backend teams build more capability to the system without interference. 

***Advantages:***

 1. **Modularity** - The architecture allows teams to work or improve the application at different pace while building independent modules that may or may not be released with the current version without disrupting the app or other team.
 2. **Resource and Code Optimization** - Frontend web pages can be downloaded once allowing fewer processing to be done on server side on each request. Likewise code can be optimized independently to make the application more responsive or faster.
 3. **Switchable Frameworks** -  able to completely change underlying design tools and frameworks used.
 4. **Faster Deployment** - Promotes rapid development and prototyping of features either at the frontend or backend as developers can freely commit completed work
 5. **Consolidation of API** - Able to serve non web based request using same backend services.
 6. **Easy to Upgrade and Migrate** - Upgrading frontend or backend doesn't break the application as these can be done independently and with better version controlling.
 7. **Scalability** - Ability to upgrade resources for either frontend or backend independently and at varying pace. 


***Disadvantages/Bottlenecks***

1. **Security and Sessions** - More effort is required to protect the backend from unwanted access or hacks.
2. **Cost** - It may require more teams to handle each part of the application stack. Likewise cost of infrastructure required to support the seperate applications may double.

## Stack


**Front-end Stack and Dependencies**
Front-end is a Javascript SPA powered by VUE Js.
 1. Vue Js, Vue Router and Vue Cli 3.0
 2. Vuetify 1.5
 3. Webpack 4
 4. Babel
 5. Node 11
 6. Npm 6.8
 7. Pusher Js 

**Back-end Stack and Dependencies**
Back-end is a Rest based API powered by PHP 7 and Lumen (Laravel Micro Framework).

 1. PHP 7.2
 2. Lumen 5.7
 3. Pusher PHP Server
 4. Nicklaw twitch API
 5. Composer

The Application can be deployed on both Nginx and Apache capable servers.


## Security
The application utilizes access tokens to verify the authenticity of request for access to protected resources. Token generation is provided by Twitcher Oauth2 services using the Oauth [Authorization Code Workflow](https://tools.ietf.org/html/rfc6749#section-1.3.1).  The server acts as a delegate to handle the initial credential exchange thus efficiently hiding client credential details from the frontend.


## Deployment

**
At the current scale, a single AWS EC2 instance partitioned to serve both frontend and backend using subdomains or IP delegation should suffice. However, to meet high server loads the ideal configuration will include.
1. 2 or more EC2 instances placed in multiple Availability Zones using dynamic auto scaling.
2. ELB for load balancing
3. Route53 to manage DNS 
4. Cloudfront and S3 for caching static content such as compiled js, css and images.
5.  If required AWS SNS could be used to replace Pusher. They both serve the same purpose and will perform at scale.
6. Typical pubsub will require use of queue systems to delegate complex operations to. With high traffic loads, Amazon SQS can be used to queue notifications
7. Use Containerization with Docker to speed up deployment or instance spinups
8.  Leverage the orchastration ability of AEB (Amazon Elastic Beanstalk)


> Written with [StackEdit](https://stackedit.io/).