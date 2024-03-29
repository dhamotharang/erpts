# Generated automatically from Makefile.in by configure.
#
# Makefile.in for Kannel, the Open Source WAP and SMS Gateway.
#
# This file is currently maintained manually. There is currently
# no generation out of automake. This will change as soon as we
# switch to our new autoconf/configure build process.
#

SHELL = /bin/sh

srcdir = .
top_srcdir = .
prefix = /usr/local
exec_prefix = ${prefix}

bindir = ${exec_prefix}/bin
sbindir = ${exec_prefix}/sbin
libexecdir = ${exec_prefix}/libexec
datadir = ${prefix}/share
sysconfdir = ${prefix}/etc
sharedstatedir = ${prefix}/com
localstatedir = ${prefix}/var
libdir = ${exec_prefix}/lib
infodir = ${prefix}/info
mandir = ${prefix}/man
includedir = ${prefix}/include
oldincludedir = /usr/include

docdir = ${prefix}/share/doc/kannel

DESTDIR =

pkgdatadir = $(datadir)/@PACKAGE@
pkglibdir = $(libdir)/@PACKAGE@
pkgincludedir = $(includedir)/@PACKAGE@

top_builddir = .

ACLOCAL = @ACLOCAL@
AUTOCONF = @AUTOCONF@
AUTOHEADER = @AUTOHEADER@

INSTALL = /usr/bin/install -c
INSTALL_PROGRAM = ${INSTALL} $(AM_INSTALL_PROGRAM_FLAGS)
INSTALL_DATA = ${INSTALL} -m 644
INSTALL_SCRIPT = ${INSTALL_PROGRAM}
transform = s,x,x,

NORMAL_INSTALL = :
PRE_INSTALL = :
POST_INSTALL = :
NORMAL_UNINSTALL = :
PRE_UNINSTALL = :
POST_UNINSTALL = :
CC = gcc
MAKEINFO = @MAKEINFO@
PACKAGE = @PACKAGE@
RANLIB = ranlib
SHELL = /bin/sh
VERSION = 1.4.0
SUFFIX = 
LEX = lex
PERL = /usr/bin/perl
YACC = yacc

# -v gives verbose output.
YFLAGS = -d -p ws_yy_


mkinstalldirs = $(SHELL) $(top_srcdir)/mkinstalldirs
CONFIG_HEADER = gw-config.h
CONFIG_CLEAN_FILES =


LIBOBJS=
LIBSRCS=$(LIBOBJS:.o=.c)

LIBS=-lrt -lresolv -lnsl -lm  -lpthread -L/usr/lib -lxml2 -lz -lpthread -lm
CFLAGS=-D_REENTRANT=1 -I. -Igw  -D_XOPEN_SOURCE=600 -D_BSD_SOURCE -I/usr/include/libxml2
LDFLAGS= -rdynamic

MKDEPEND=$(CC) $(CFLAGS) -MM

JADE=jade
JADETEX=no
PDFJADETEX=no
DVIPS=no
FIG2DEV=no
CONVERT=no

HTML_DSL=
TEX_DSL=

# Set this to something if you want all installed binaries to have a suffix.
# Version number is common.
suffix = $(SUFFIX)

#
# You probably don't need to touch anything below this, if you're just
# compiling and installing the software.
#

STARTSTOPDAEMONSRC=

binsrcs = \
	wmlscript/wmlsc.c \
	wmlscript/wmlsdasm.c \
	utils/seewbmp.c \
	utils/mtbatch.c
sbinsrcs = \
	gw/bearerbox.c \
	gw/smsbox.c \
	gw/wapbox.c \
	utils/run_kannel_box.c \
	$(STARTSTOPDAEMONSRC)
progsrcs = $(binsrcs) $(sbinsrcs)
progobjs = $(progsrcs:.c=.o)
progs = $(progsrcs:.c=)
binprogs = $(binsrcs:.c=) gw-config
sbinprogs = $(sbinsrcs:.c=)

gwsrcs = $(wildcard gw/*.c) $(wildcard gw/smsc/*.c)
gwobjs = $(gwsrcs:.c=.o)

libsrcs = $(wildcard gwlib/*.c) $(LIBSRCS)
libobjs = $(libsrcs:.c=.o) $(LIBOBJS)

wapsrcs = $(wildcard wap/*.c) $(wildcard radius/*.c)
wapobjs = $(wapsrcs:.c=.o)

wmlscriptsrcs = $(wildcard wmlscript/*.c)
wmlscriptobjs = $(wmlscriptsrcs:.c=.o)

testsrcs = $(wildcard test/*.c)
testobjs = $(testsrcs:.c=.o)
testprogs = $(testsrcs:.c=)
tests = $(testprogs) $(wildcard test/*.sh)

checksrcs = $(wildcard checks/*.c)
checkobjs = $(checksrcs:.c=.o)
checkprogs = $(checksrcs:.c=)
checks = $(checkprogs) $(wildcard checks/*.sh)

benchformats = \
    benchmarks/report.pdf \
    benchmarks/report.ps \
    benchmarks/report.html
benchscripts = benchmarks/run-benchmarks $(wildcard benchmarks/*.sh)
benchoutputs = \
    $(benchformats) \
    $(wildcard benchmarks/*.ps) \
    $(wildcard benchmarks/*.png) \
    $(wildcard benchmarks/*.xml)

srcs = $(wildcard */*.c)
objs = $(srcs:.c=.o)

libs = libgw.a libwmlscript.a libwap.a libgwlib.a

srcdirs = gw gw/smsc gwlib test utils wmlscript checks wap radius

man1pages = utils/seewbmp.1 utils/mtbatch.1 wmlscript/wmlsc.1 wmlscript/wmlsdasm.1
man8pages = gw/kannel.8 utils/run_kannel_box.8

docsrcs = $(wildcard grep -l '<!DOCTYPE ' doc/*/*.xml)
docs = $(docsrcs:.xml=.html) $(docsrcs:.xml=.rtf) $(docsrcs:.xml=.ps) $(docsrcs:.xml=.pdf)
DOCSTARGET=no-docs
DOCDRAFTS=IGNORE

pssrcs = $(wildcard doc/alligata/*.png doc/wtls/*.png)
ps = $(pssrcs:.png=.ps)  

figsrcs = $(wildcard doc/userguide/*.fig doc/arch/*.fig)
figs = $(figsrcs:.fig=.png)  $(figsrcs:.fig=.ps)


.SUFFIXES: $(SUFFIXES) .xml .html .rtf .ps .fig .png .y .c .o .pdf

.xml.html:
	sed "s/#FIGTYPE#/.png/;s/#VERSION#/${VERSION}/;s/#DATE#/`date +%Y.%m.%d`/;s/#DRAFTS#/${DOCDRAFTS}/" $< > $*.tmp
	${JADE} -V nochunks -t sgml -d $(HTML_DSL) $*.tmp > $@
	rm -f $*.tmp

.xml.rtf:
	sed "s/#FIGTYPE#/.ps/;s/#VERSION#/${VERSION}/;s/#DATE#/`date +%Y.%m.%d`/;s/#DRAFTS#/${DOCDRAFTS}/" $< > $*.tmp
	cd `dirname $<` && $(JADE) -o `basename $*`.rtf -t rtf -d $(TEX_DSL) `basename $*`.tmp
	rm -f $*.tmp

.xml.ps:
	sed "s/#FIGTYPE#/.ps/;s/#VERSION#/${VERSION}/;s/#DATE#/`date +%Y.%m.%d`/;s/#DRAFTS#/${DOCDRAFTS}/" $< > $*.tmp
	$(JADE) -o $*.tex -t tex -d $(TEX_DSL) $*.tmp
	rm -f $*.tmp
	cd `dirname $<` && $(JADETEX) `basename $*`.tex >/dev/null || true
	cd `dirname $<` && $(JADETEX) `basename $*`.tex >/dev/null || true
	cd `dirname $<` && $(JADETEX) `basename $*`.tex >/dev/null || \
	    ( echo Check `dirname $<`/`basename $*`.log for errors && false)
	rm -f $*.log
	cd `dirname $<` && $(DVIPS) -q -o `basename $*`.ps `basename $*`.dvi
	rm -f $*.dvi $*.tex $*.aux 

.xml.pdf:
	sed "s/#FIGTYPE#/.png/;s/#VERSION#/${VERSION}/;s/#DATE#/`date +%Y.%m.%d`/;s/#DRAFTS#/${DOCDRAFTS}/" $< > $*.tmp
	$(JADE) -o $*.tex -t tex -d $(TEX_DSL) $*.tmp
	rm -f $*.tmp
	cd `dirname $<` && $(JADETEX) `basename $*`.tex >/dev/null || true
	cd `dirname $<` && $(JADETEX) `basename $*`.tex >/dev/null || true
	cd `dirname $<` && $(JADETEX) `basename $*`.tex >/dev/null || true
	rm -f $*.log $*.dvi
	cd `dirname $<` && $(PDFJADETEX) `basename $*`.tex > /dev/null || true 
	test -r $*.pdf || false
	rm -f $*.log $*.tex $*.aux $*.out

.fig.png:
	$(FIG2DEV) -Lpng $< $@

.fig.ps:
	$(FIG2DEV) -Lps $< $@

.png.ps:
	$(CONVERT) $< $@

.y.c:
	$(YACC) $(YFLAGS) $< && mv y.tab.c $*.c
	if test -f y.tab.h; then \
	if cmp -s y.tab.h $*.h; then rm y.tab.h; else mv y.tab.h $*.h; fi; \
	else :; fi

.c.o:
	$(CC) $(CFLAGS) -o $@ -c $<

all: progs $(testprogs) $(checkprogs) $(DOCSTARGET) gw-config
progs: $(progs)
tests: $(testprogs)
docs: figs ps $(docs)
no-docs:
figs: $(figs)
ps: $(ps)

check: all
	utils/run-checks $(checks)

bench: all $(benchformats)
benchmarks/report.xml: dummy
	benchmarks/run-benchmarks benchmarks/*.sh
dummy:

install: all
	$(INSTALL) -d $(DESTDIR)$(bindir)
	for prog in $(binprogs); do \
		$(INSTALL) $$prog \
		    $(DESTDIR)$(bindir)/`basename $$prog`$(suffix); \
	done
	$(INSTALL) -d $(DESTDIR)$(sbindir)
	for prog in $(sbinprogs); do \
		$(INSTALL) $$prog \
		    $(DESTDIR)$(sbindir)/`basename $$prog`$(suffix); \
	done
	$(INSTALL) -d $(DESTDIR)$(mandir)/man1
	$(INSTALL) $(man1pages) $(DESTDIR)$(mandir)/man1
	$(INSTALL) -d $(DESTDIR)$(mandir)/man8
	$(INSTALL) $(man8pages) $(DESTDIR)$(mandir)/man8
	$(INSTALL) -d $(DESTDIR)$(includedir)/kannel/gwlib
	$(INSTALL_DATA) $(top_srcdir)/gwlib/*.h $(DESTDIR)$(includedir)/kannel/gwlib
	$(INSTALL_DATA) gw-config.h $(DESTDIR)$(includedir)/kannel
	$(INSTALL) -d $(DESTDIR)$(libdir)/kannel
	$(INSTALL_DATA) libgwlib.a $(DESTDIR)$(libdir)/kannel

install-test: all
	$(INSTALL) -d $(DESTDIR)$(libdir)/kannel
	$(INSTALL) -d $(DESTDIR)$(libdir)/kannel/test
	(cd test && find . -type f ! -name "*.c" ! -name "*.o" | grep -v ".cvsignore" | grep -v "/CVS/" | while read a ; do $(INSTALL_DATA) $$a $(DESTDIR)$(libdir)/kannel/test ; done)
	for prog in $(tests) ; do \
                chmod 755 $(DESTDIR)$(libdir)/kannel/$$prog ; \
        done

install-checks: all
	$(INSTALL) -d $(DESTDIR)$(libdir)/kannel/checks
	(cd checks && find . -type f ! -name "*.c" ! -name "*.o" | grep -v ".cvsignore" | grep -v "/CVS/" | while read a ; do $(INSTALL_DATA) $$a $(DESTDIR)$(libdir)/kannel/checks ; done)
	for prog in $(checks) ; do \
                chmod 755 $(DESTDIR)$(libdir)/kannel/$$prog ; \
        done

install-contrib: all
	$(INSTALL) -d $(DESTDIR)$(docdir)/contrib
	(cd contrib && find . -type d ! -name "CVS" | while read a ; do $(INSTALL) -d $(DESTDIR)$(docdir)/contrib/$$a ; done )
	(cd contrib && find . -type f ! -name ".cvsignore" | grep -v "/CVS/" | while read a ; do $(INSTALL_DATA) $$a $(DESTDIR)$(docdir)/contrib/$$a ; done )
	find $(DESTDIR)$(docdir)/contrib/ -name "*.pl" -o -name "*.sh" -o -name "*.cgi" -o -name "sendsms" -o -name "kannel.monitor" | while read a ; do chmod 755 "$$a" ; done

install-docs:
	$(INSTALL) -d $(DESTDIR)$(docdir)/examples
	$(INSTALL_DATA) doc/examples/*.conf $(DESTDIR)$(docdir)/examples
	for docfile in userguide alligata wtls ; do \
		$(INSTALL) -d $(DESTDIR)$(docdir)/$$docfile ; \
		$(INSTALL_DATA) doc/$$docfile/$$docfile.ps $(DESTDIR)$(docdir)/$$docfile ; \
		$(INSTALL_DATA) doc/$$docfile/$$docfile.html $(DESTDIR)$(docdir)/$$docfile ; \
		$(INSTALL_DATA) doc/$$docfile/$$docfile.rtf $(DESTDIR)$(docdir)/$$docfile ; \
		$(INSTALL_DATA) doc/$$docfile/$$docfile.pdf $(DESTDIR)$(docdir)/$$docfile ; \
		$(INSTALL_DATA) doc/$$docfile/*.png $(DESTDIR)$(docdir)/$$docfile ; \
	done
clean:
	rm -f */*.o gw/smsc/*.o *.a core gw-config $(progs) $(testprogs) $(checkprogs)
	rm -f $(figs) $(ps) $(docs)
	rm -f $(benchoutputs)

distclean: clean
	rm -f Makefile gw-config.h config.cache config.log config.status .depend gwlib/gw_uuid_types.h

nag:
	utils/find-long-lines

depend .depend: wmlscript/wsgram.h gw-config.h
	for dir in $(srcdirs); do \
		$(MKDEPEND) $$dir/*.c | sed "s:^[^ ]:$$dir/&:"; done > .depend
include .depend

libgw.a: $(gwobjs)
	ar rc libgw.a $(gwobjs)
	$(RANLIB) libgw.a

libgwlib.a: $(libobjs)
	ar rc  libgwlib.a $(libobjs)
	$(RANLIB) libgwlib.a

libwmlscript.a: $(wmlscriptobjs)
	ar rc libwmlscript.a $(wmlscriptobjs)
	$(RANLIB) libwmlscript.a

libwap.a: $(wapobjs)
	ar rc libwap.a $(wapobjs)
	$(RANLIB) libwap.a

wmlscript/wsgram.h: wmlscript/wsgram.c

make-op-table: $(srcdir)/wmlscript/make-op-table.in
	sed 's%@PERLPROG@%/usr/bin/perl%g' $(srcdir)/wmlscript/make-op-table.in \
		> make-op-table
	chmod a+x make-op-table

opcodes:
	./make-op-table $(srcdir)/wmlscript/wsasm.h \
		> $(srcdir)/wmlscript/wsopcodes.h

$(progs): $(libs) $(progobjs)
	$(CC) $(CFLAGS) $(LDFLAGS) -o $@ $(@:=).o $(libs) $(LIBS)

$(testprogs): $(testobjs) $(libs)
	$(CC) $(CFLAGS) $(LDFLAGS) -o $@ $(@:=).o $(libs) $(LIBS)

$(checkprogs): $(checkobjs) $(libs)
	$(CC) $(CFLAGS) $(LDFLAGS) -o $@ $(@:=).o $(libs) $(LIBS)

pkg: all
	(cd solaris; mk-solaris-package.sh)
	echo Package is now in solaris directory

gw-config: utils/foobar-config.sh Makefile
	./utils/foobar-config.sh "-I$(includedir)/kannel  -D_XOPEN_SOURCE=600 -D_BSD_SOURCE -I/usr/include/libxml2" \
		"-L$(libdir)/kannel -lgwlib -lrt -lresolv -lnsl -lm  -lpthread -L/usr/lib -lxml2 -lz -lpthread -lm" \
		"1.4.0" > gw-config
	chmod 0755 gw-config
