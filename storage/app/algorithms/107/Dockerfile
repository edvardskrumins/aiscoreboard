FROM ubuntu:14.04
ADD result /
ADD ads.csv /
ADD slots.csv /
RUN chmod 777 ads.csv
RUN chmod 777 slots.csv
RUN chmod 777 result
CMD ["/result"]
